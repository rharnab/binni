<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\CompanyVaultTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\User;


class ValultTransactionController extends Controller
{
    /**
     * Check Authencticate user
     *
     */
    public function __construct(){
        $this->middleware('auth');
    }


    /**
     * Show All Vault Transaction
     *
     */
    public function index(){
        $vault_transactions = DB::table('company_vault_transactions as cvt')
                    ->select(
                        'a.name  as account_name', 
                        'cvt.id',
                        'cvt.transaction_date', 
                        'cvt.transaction_type', 
                        'cvt.amount',
                        'cvt.remarks',
                        'cvt.created_at',
                        'cvt.status'
                    )
                    ->leftJoin('accounts as a', 'cvt.voult_account_id', 'a.id')
                    ->where('cvt.company_id', Auth::user()->company_id)
                    ->get();
        $data = [
            "vault_transactions" => $vault_transactions
        ];
        return view('transaction.vault.index', $data);
    }


    /**
     * Redirect to vault transaction create page
     *
     */
    public function create(){
        $account_info = DB::table('predefined_mapping_gls as pmg')
                    ->select(
                        'pmg.account_id',
                        'pmg.transaction_type',
                        'a.name  as account_name'
                    )
                    ->leftJoin('predefined_gls as pg', 'pmg.predefined_gl_id', 'pmg.id')
                    ->leftJoin('accounts as a', 'pmg.account_id', 'a.id')
                    ->where('pmg.company_id', Auth::user()->company_id)
                    ->where('pg.gl_type', 'company_vault')
                    ->first();
                    
        $data = [
            "account_info" => $account_info
        ];


        return view('transaction.vault.create', $data);
    }




     /**
     * Vault Transaction Store
     *
     */
    public function store(Request $request){    
        $company_vault_transaction                   = new CompanyVaultTransaction();
        $company_vault_transaction->company_id       = Auth::user()->company_id;
        $company_vault_transaction->voult_account_id = $request->input('voult_account_id');
        $company_vault_transaction->transaction_date = date('Y-m-d', strtotime($request->input('transaction_date')));
        $company_vault_transaction->transaction_type = $request->input('transaction_type');
        $company_vault_transaction->amount           = $request->input('amount');
        $company_vault_transaction->remarks          = $request->input('remarks');
        $company_vault_transaction->status           = 0;
        $company_vault_transaction->created_by       = Auth::user()->id;

        try{
            $company_vault_transaction->save();
            Toastr::success('Vault Transaction Successfully :)','Success');
            return redirect()->route('transaction.vault.index');
        }catch(Exception $e){
            Toastr::error($e->getMessage(),'Failed');
            return redirect()->back();
        }
    }


    /**
     * pending all vault transaction
     *
     */
    public function pending(){
        $vault_transactions = DB::table('company_vault_transactions as cvt')
                    ->select(
                        'a.name  as account_name', 
                        'cvt.id',
                        'cvt.transaction_date', 
                        'cvt.transaction_type', 
                        'cvt.amount',
                        'cvt.remarks',
                        'cvt.created_at', 
                        'cvt.status'
                    )
                    ->leftJoin('accounts as a', 'cvt.voult_account_id', 'a.id')
                    ->where('cvt.company_id', Auth::user()->company_id)
                    ->where('cvt.status', 0)
                    ->get();
        $data = [
            "vault_transactions" => $vault_transactions
        ];
        return view('transaction.vault.pending', $data);
    }


    /**
     * Authorize vault transaction
     *
     */
    public function authorizeTranaction(Request $request){
        if($request->has('vault_transactions')){
            $vault_transactions = $request->input('vault_transactions');
            foreach($vault_transactions as $vault_transaction){
                if($this->insertTransactionTable($vault_transaction) === true){
                    try{
                        DB::table('company_vault_transactions')->where('id', $vault_transaction)->update([
                            "status"      => 1,
                            "approved_by" => Auth::user()->id,
                            "approved_at" => date('Y-m-d H:i:s')
                        ]);
                        $data = [
                            "status" => 200,
                            "message" => "Vault Transaction Authorize Successfully"
                        ];
                    }catch(Exception $e){
                        $data = [
                            "status" => 500,
                            "message" => $e->getMessage()
                        ];
                    }  
                }else{
                    $data = [
                        "status" => 200,
                        "message" => "Vault Transaction Authorize Failed"
                    ];                    
                }
                              
            }
            return json_encode($data);
        }
    }




    /**
     * Transaction table information store & account balance calculation
     *
     */
    public function insertTransactionTable($vault_transaction_id){
        $vault_info = DB::table('company_vault_transactions')
                    ->where('id', $vault_transaction_id)
                    ->where('company_id', Auth::user()->company_id)
                    ->first();

        $account_info = DB::table('accounts')
                    ->where('company_id', Auth::user()->company_id)
                    ->where('id', $vault_info->voult_account_id)
                    ->first();

        $current_credit_amount = 0;
        $current_debit_amount  = 0;
        if($vault_info->transaction_type == 'cr'){        
           $current_credit_amount = $account_info->total_cr_balance + $vault_info->amount;
           $current_amount        = $account_info->current_balance + $vault_info->amount;

           $dr_account_id = 0;
           $dr_amount     = 0;
           $cr_account_id = $vault_info->voult_account_id;
           $cr_amount     = $vault_info->amount;

        }elseif($vault_info->transaction_type == 'dr'){
            $current_debit_amount = $account_info->total_dr_balance + $vault_info->amount;
            $current_amount        = $account_info->current_balance - $vault_info->amount;

            $dr_account_id = $vault_info->voult_account_id;
            $dr_amount     = $vault_info->amount;
            $cr_account_id = 0;
            $cr_amount     = 0;
        }

        $transaction                   = new Transaction();
        $transaction->company_id       = Auth::user()->company_id;
        $transaction->transaction_date = $vault_info->transaction_date;
        $transaction->dr_account_id    = $dr_account_id;
        $transaction->dr_amount        = $dr_amount;
        $transaction->cr_account_id    = $cr_account_id;
        $transaction->cr_amount        = $cr_amount;
        $transaction->remarks          = $vault_info->remarks;
        $transaction->status           = 1;
        $transaction->created_by       = $vault_info->created_by;
        $transaction->updated_by       = $vault_info->updated_by;
        $transaction->approved_by      = Auth::user()->id;
        $transaction->created_at       = $vault_info->created_at;
        $transaction->updated_at       = $vault_info->updated_at;
        $transaction->approved_at      = date('Y-m-d H:i:s');

        try{
            $transaction->save();

            try{
                DB::table('accounts')->where('company_id', Auth::user()->company_id)->where('id', $vault_info->voult_account_id)->update([
                    "total_dr_balance" => $current_debit_amount,
                    "total_cr_balance" => $current_credit_amount,
                    "current_balance"  => $current_amount,
                    "updated_at"       => date('Y-m-d H:i:s')
                ]);
                return true;
            }catch(Exception $e){
                return false;
            }

        }catch(Exception $e){
            return false;
        }

    }
    

    /**
     * Reject vault transaction
     *
     */
    public function rejectTransaction(Request $request){
        if($request->has('vault_transactions')){
            $vault_transactions = $request->input('vault_transactions');
            foreach($vault_transactions as $vault_transaction){
                try{
                    DB::table('company_vault_transactions')->where('id', $vault_transaction)->update([
                        "status"      => 3,
                        "approved_by" => Auth::user()->id,
                        "approved_at" => date('Y-m-d H:i:s')
                    ]);
                    $data = [
                        "status" => 200,
                        "message" => "Vault Transaction Authorize Successfully"
                    ];
                }catch(Exception $e){
                    $data = [
                        "status" => 500,
                        "message" => $e->getMessage()
                    ];
                }  
                              
            }
            return json_encode($data);
        }
    }







}

