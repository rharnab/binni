<?php

namespace App\Http\Controllers\ParameterSetup;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\PredefinedGl;
use App\Models\PredefinedMappingGl;

class PredefinedGlController extends Controller
{
    /**
     * Check Authencticate user
     *
     */
    public function __construct(){
        $this->middleware('auth');
    }


    /**
     * Show ALl Predefined GL
     *
     */
    public function index(){
        $mapping_gls = DB::table('predefined_mapping_gls as pmg')
                    ->select(
                        'pmg.id',
                        'pg.name as predefined_gl_name',
                        'a.name as mapping_account_name',
                        'pmg.transaction_type',
                        'pmg.remarks',
                        'pmg.status'
                    )
                    ->leftJoin('predefined_gls as pg', 'pmg.predefined_gl_id', 'pg.id')
                    ->leftJoin('accounts as a', 'pmg.account_id', 'a.id')
                    ->where('pmg.company_id', Auth::user()->company_id)
                    ->get();

        $data = [
            "mapping_gls" => $mapping_gls
        ];

        return view('parameter-setup.predefined-gl.index', $data);
    }


    /**
     * Redirect to gl create page
     *
     */
    public function create(){
        $predefined_accounts = PredefinedGl::select('id','name')->where('company_id', Auth::user()->company_id)->get();
        $accounts            = Account::select('id','name')->where('company_id', Auth::user()->company_id)->get();
        $data                = [
            "predefined_accounts" => $predefined_accounts,
            "accounts"            => $accounts
        ];
        return view('parameter-setup.predefined-gl.create', $data);
    }

    /**
     * Store Predefined Gl Mapping
     *
     */
    public function store(Request $request){
        if( $this->alreadyExistsPredefinedGl($request->input('predefined_gl_id')) === false ){
            
            $predefined_mapping_gl                   = new PredefinedMappingGl();
            $predefined_mapping_gl->company_id       = Auth::user()->company_id;
            $predefined_mapping_gl->predefined_gl_id = $request->input('predefined_gl_id');
            $predefined_mapping_gl->account_id       = $request->input('account_id');
            $predefined_mapping_gl->transaction_type = $request->input('transaction_type');
            $predefined_mapping_gl->remarks          = $request->input('remarks');
            $predefined_mapping_gl->status           = 0;
            $predefined_mapping_gl->created_by       = Auth::user()->id;

            try{
                $predefined_mapping_gl->save();
                Toastr::success('Predefined GL Mapping Successfully :)','Success');
                return redirect()->route('parameter_setup.predefined_gl.index');
            }catch(Exception $e){
                Toastr::error($e->getMessage(),'Failed');
                return redirect()->back();  
            }

        }else{
            Toastr::error("Your selected predefined gl type already mapping",'Failed');
            return redirect()->back();  
        }
    }


    /**
     * Check gl type already mapping or not
     *
     */
    public function alreadyExistsPredefinedGl($predefined_gl){
        $check_gl_count = PredefinedMappingGl::select('id')->where('company_id', Auth::user()->company_id)->where('predefined_gl_id', $predefined_gl)->count();
        if($check_gl_count > 0){
            return true;
        }else{
            return false;
        }
    }


    /**
     * redirect to the pending predefiend gl mapping authorization
     *
     */
    public function pending(){
        $mapping_gls = DB::table('predefined_mapping_gls as pmg')
                ->select(
                    'pmg.id',
                    'pg.name as predefined_gl_name',
                    'a.name as mapping_account_name',
                    'pmg.transaction_type',
                    'pmg.remarks',
                    'pmg.status'
                )
                ->leftJoin('predefined_gls as pg', 'pmg.predefined_gl_id', 'pg.id')
                ->leftJoin('accounts as a', 'pmg.account_id', 'a.id')
                ->where('pmg.company_id', Auth::user()->company_id)
                ->where('pmg.status', 0)
                ->get();

        $data = [
            "mapping_gls" => $mapping_gls
        ];

        return view('parameter-setup.predefined-gl.pending', $data);
    }


    /**
     * Authorize predefined mapping gl
     *
     */
    public function authorizeGlMapping(Request $request){
        if($request->has('gls')){
            $gls = $request->input('gls');
            foreach($gls as $gl){
                try{
                    DB::table('predefined_mapping_gls')->where('id', $gl)->update([
                        "status"      => 1,
                        "approved_by" => Auth::user()->id,
                        "approved_at" => date('Y-m-d H:i:s')
                    ]);
                    $data = [
                        "status" => 200,
                        "message" => "Mapping GL Authorization Successfully"
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
     * Reject predefined mapping gl
     *
     */
    public function rejectGlMapping(Request $request){
        if($request->has('gls')){
            $gls = $request->input('gls');
            foreach($gls as $gl){
                try{
                    DB::table('predefined_mapping_gls')->where('id', $gl)->update([
                        "status"      => 3,
                        "approved_by" => Auth::user()->id,
                        "approved_at" => date('Y-m-d H:i:s')
                    ]);
                    $data = [
                        "status" => 200,
                        "message" => "Mapping GL Reject Successfully"
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
