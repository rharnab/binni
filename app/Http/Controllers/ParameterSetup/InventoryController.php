<?php

namespace App\Http\Controllers\ParameterSetup;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\User;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    /**
     * Check Authencticate user
     *
     */
    public function __construct(){
        $this->middleware('auth');
    }


    /**
     * Show ALl Inventory Setup
     *
     */
    public function index(){  
        $inventories = DB::table('inventories')
                ->select(
                    'id',
                    'name',
                    'short_description',
                    'image',
                    'asset_type',
                    'depreciation_value',
                    'status'
                )
                ->where('company_id', Auth::user()->company_id)
                ->orderBy('id', 'desc')
                ->get();  
        $data = [
            "inventories" => $inventories
        ];    
        return view('parameter-setup.inventory.index', $data);
    }

     /**
     * Redirect to inventory create page
     *
     */
    public function create(){
        return view('parameter-setup.inventory.create');
    }


    /**
     * Store inventory item
     *
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'asset_type' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }


        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $fileName   = time() .uniqid(). '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(300, 200, function ($constraint) {
                $constraint->aspectRatio();                 
            });

            $img->stream(); // <-- Key point

            //dd();
            Storage::disk('local')->put('images/inventory/'.$fileName, $img,);

            $image_path = "images/inventory/{$fileName}";
        }else{
            $image_path = "";
        }

        $depreciation_value = $request->input('depreciation_value') ?? 0;

        $inventory                     = new Inventory();
        $inventory->company_id         = Auth::user()->company_id;
        $inventory->name               = $request->input('name');
        $inventory->image              = $image_path;
        $inventory->short_description  = $request->input('short_description');
        $inventory->description        = $request->input('description');
        $inventory->asset_type         = $request->input('asset_type');
        $inventory->depreciation_value = $depreciation_value;
        $inventory->status             = 0;
        $inventory->created_by         = Auth::user()->id;

        try{
            $inventory->save();
            Toastr::success('Inventory Item Created Successfully :)','Success');
            return redirect()->route('parameter_setup.inventory.index');
        }catch(Exception $e){
            Toastr::error($e->getMessage(),'Failed');
            return redirect()->back();
        }
    }


    /**
     * redirect to the pending inventory authorization
     *
     */
    public function pending(){
        $inventories = DB::table('inventories')
                ->select(
                    'id',
                    'name',
                    'short_description',
                    'image',
                    'asset_type',
                    'depreciation_value',
                    'status'
                )
                ->where('company_id', Auth::user()->company_id)
                ->where('status', 0)
                ->orderBy('id', 'desc')
                ->get();  
        $data = [
            "inventories" => $inventories
        ];    
        return view('parameter-setup.inventory.pending', $data);
    }



    /**
     * Authorize predefined inventory
     *
     */
    public function authorizeInventory(Request $request){
        if($request->has('inventories')){
            $inventories = $request->input('inventories');
            foreach($inventories as $inventory){
                try{
                    DB::table('inventories')->where('id', $inventory)->update([
                        "status"      => 1,
                        "approved_by" => Auth::user()->id,
                        "approved_at" => date('Y-m-d H:i:s')
                    ]);
                    $data = [
                        "status" => 200,
                        "message" => "Inventory Authorization Successfully"
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
     * Reject predefined inventory
     *
     */
    public function rejectInventory(Request $request){
        if($request->has('inventories')){
            $inventories = $request->input('inventories');
            foreach($inventories as $inventory){
                try{
                    DB::table('inventories')->where('id', $inventory)->update([
                        "status"      => 3,
                        "approved_by" => Auth::user()->id,
                        "approved_at" => date('Y-m-d H:i:s')
                    ]);
                    $data = [
                        "status" => 200,
                        "message" => "Inventory Reject Successfully"
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
