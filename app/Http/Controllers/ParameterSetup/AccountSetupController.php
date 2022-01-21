<?php

namespace App\Http\Controllers\ParameterSetup;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Employee;

class AccountSetupController extends Controller
{
    /**
     * Check Authencticate user
     *
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show ALl Account List
     *
     */
    public function index(){
        $accounts = DB::table('accounts as a')
                ->select('a.*','aa.name as parent_accont_name','at.name as account_type_name')
                ->leftJoin('accounts as aa', 'a.immediate_parent', 'aa.id')
                ->leftJoin('account_types as at', 'a.account_type_id', 'at.id')
                ->where('a.company_id', Auth::user()->company_id)
                ->orderBy('a.id','desc')
                ->get();     
        $data = [
            "accounts" => $accounts
        ];
        return view('parameter-setup.account-setup.index', $data);
    }

    /**
     * Account Create Page
     *
     */
    public function create(){
        $account_types = AccountType::get();
        $data = [
            "account_types" => $account_types
        ];
        return view('parameter-setup.account-setup.create', $data);
    }

    /**
     * Search Parent Account
     *
     */
    public function searchParentAccount(Request $request){
        $acc_level = $request->input('acc_level') - 1;
        $acc_types = $request->input('acc_types');
        $datas = DB::table('accounts')
                ->select('id', 'name')
                ->where('company_id',Auth::user()->company_id)
                ->where('account_level', $acc_level)
                ->where('account_type_id', $acc_types)
                ->where('status', 1)
                ->get();
        $output = "<option value=''>Select Parent Account</option>";
        if($datas->count() > 0){
            foreach($datas as $data){
                $output .= "<option value='{$data->id}'>{$data->name}</option>";
            }
        }else{
            $output .= "<option value=''>Parent Account Not Found</option>";          
        }

        return $output;
    }


    /**
     * Store Account Information
     *
     */
    public function store(Request $request){

        if(!empty($request->input('immidiate_parent')) ){
            $immidiate_parent = $request->input('immidiate_parent');
        }else{
            $immidiate_parent = 0;
        }

        $account = new Account();

        $account->company_id               = Auth::user()->company_id;
        $account->name                     = $request->input('name');
        $account->account_type_id          = $request->input('acc_types');
        $account->acc_code                 = $this->maximumAccountCode();
        $account->account_level            = $request->input('acc_level');
        $account->immediate_parent         = $immidiate_parent;
        $account->allow_manual_transaction = $request->input('allow_manual_transction');
        $account->allow_negative_balance   = $request->input('allow_negetive_transction');
        $account->created_by               = Auth::id();
        $account->status                   = 0;

        try{
            $save_account = $account->save();
            Toastr::success('Account Setup Successfully :)','Success');
            return redirect()->route('parameter_setup.account_setup.index');
        }catch(\Exception $e){
            Toastr::error($e->getMessage(),'Failed');
            return redirect()->back();
        }

    }

    /**
     * This function return table maximum account number
     *
     */
    private function maximumAccountCode(){
        $company_id   = Auth::user()->company_id;
        $max_acc_code = DB::table('accounts')
        ->select('acc_code')
        ->where('acc_code', DB::raw("(select max(`acc_code`) from accounts where company_id='$company_id' limit 1)"))
        ->first();
     
        $account_code = $max_acc_code->acc_code ?? 100000;
        return $account_code + 1;
    }


    /**
     * This function redirect to edit page with account information
     *
     */
    public function edit($id){
        $account_info = DB::table('accounts as a')
        ->select(
            'a.id',
            'a.name',
            'a.account_level',
            'a.account_type_id',
            'a.immediate_parent',
            'a.allow_manual_transaction',
            'a.allow_negative_balance',
            'b.name as parent_name',
            'b.id as parent_id'
        )
        ->leftJoin('accounts as b','a.immediate_parent','b.id')
        ->where('a.id', $id)
        ->first();
        $account_types = AccountType::get();
        $data = [
            "account_info"  => $account_info,
            "account_types" => $account_types
        ];

        return view('parameter-setup.account-setup.edit', $data);
    }


    /**
     * Account Information Updated
     *
     */
    public function update(Request $request, $id){
        if($this->checkEditable($id) === false){
            Toastr::warning('This account number child already exists :)','Warning');
            return redirect()->route('parameter_setup.account_setup.index');
        }

        if(!empty($request->input('immidiate_parent')) ){
            $immidiate_parent = $request->input('immidiate_parent');
        }else{
            $immidiate_parent = 0;
        }

        $account = Account::find($id);
        $account->name                     = $request->input('name');
        $account->account_level                = $request->input('acc_level');
        $account->account_type_id                 = $request->input('acc_types');
        $account->immediate_parent         = $immidiate_parent;
        $account->allow_manual_transaction = $request->input('allow_manual_transction');
        $account->allow_negative_balance   = $request->input('allow_negetive_transction');
        $account->updated_by               = Auth::id();
        $account->updated_at               = date('Y-m-d H:i:s');
        $account->status                   = 0;

        try{
            $save_account = $account->save();
            Toastr::success('Account Update Successfully :)','Success');
            return redirect()->route('parameter_setup.account_setup.index');
        }catch(\Exception $e){
            Toastr::error($e->getMessage(),'Failed');
            return redirect()->route('parameter_setup.account_setup.edit', $id);
        }


    }



    /**
     * Check Account Child exists or not before modify
     *
     */
    private function checkEditable($id){
        $rowCount  = DB::table('accounts')->where('immediate_parent', $id)->count();
        if($rowCount > 0){
            return false;
        }else{
            return true;
        }
    }


    /**
     * All Pending Account 
     *
     */
    public function pending(){
        $accounts = DB::table('accounts as a')
                ->select('a.*','aa.name as parent_accont_name','at.name as account_type_name')
                ->leftJoin('accounts as aa', 'a.immediate_parent', 'aa.id')
                ->leftJoin('account_types as at', 'a.account_type_id', 'at.id')
                ->where('a.company_id', Auth::user()->company_id)
                ->where('a.status', 0)
                ->orderBy('a.id','desc')
                ->get();     
        $data = [
            "accounts" => $accounts
        ];
        return view('parameter-setup.account-setup.pending' , $data);
    }


    /**
     * Authorize Account 
     *
     */
    public function authorizeAccount(Request $request){
        if($request->has('accounts')){
            $accounts = $request->input('accounts');
            foreach($accounts as $account){
                try{
                    DB::table('accounts')->where('id', $account)->update([
                        "status"      => 1,
                        "approved_by" => Auth::user()->id,
                        "approved_at" => date('Y-m-d H:i:s')
                    ]);
                    $data = [
                        "status" => 200,
                        "message" => "Account Authorization Successfully"
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


    /**
     * Reject Account
     *
     */
    public function rejectAccount(Request $request){
        if($request->has('accounts')){
            $accounts = $request->input('accounts');
            foreach($accounts as $account){
                try{
                    DB::table('accounts')->where('id', $account)->update([
                        "status"      => 3,
                        "approved_by" => Auth::user()->id,
                        "approved_at" => date('Y-m-d H:i:s')
                    ]);
                    $data = [
                        "status" => 200,
                        "message" => "Account Reject Successfully"
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
