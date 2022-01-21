<?php

namespace App\Http\Controllers\ParameterSetup;

use App\Http\Controllers\Controller;
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


class UserController extends Controller
{
    /**
     * Check Authencticate user
     *
     */
    public function __construct(){
        $this->middleware('auth');
    }


    /**
     * Show ALl User List
     *
     */
    public function index(){

        $users = DB::table('users as u')
        ->select(
            'u.id',
            'u.name',
            'u.phone',
            'u.avatar',
            'u.email',
            'u.status',
            'r.name as role_name'
        )
        ->leftJoin('roles as r','u.role_id','r.id')
        ->where('company_id', Auth::user()->company_id)
        ->get();
        $data = [
            "users" => $users
        ];

       return view('parameter-setup.user-setup.user-list.index', $data);
    }

    /**
     * Redirect to customer create page
     *
     */
    public function create(){
        $roles = Role::all();
        $data = [
            "roles" => $roles
        ];
        return view('parameter-setup.user-setup.user-list.create', $data);
    }


     /**
     * User Information Store
     *
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'role_id'   => 'required',
            'name'      => 'required',
            'email'     => 'required|unique:users',
            'phone'     => 'required|unique:users',
        ],[
            'role_id.required'   => 'please select user access label',
            'name.required'      => 'please write user name',
            'email.required'     => 'please enter user email address',
            'email.unique'       => 'email has already exists',
            'phone.required'     => 'please enter user mobile number',
            'phone.unique'       => 'mobile number already exists'
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
            Storage::disk('local')->put('images/user-image'.'/'.$fileName, $img,);

            $image_path = "images/user-image/{$fileName}";
        }else{
            $image_path = "";
        }

        $user             = new User();
        $user->name       = $request->input('name');
        $user->phone      = $request->input('phone');
        $user->avatar     = $image_path;
        $user->email      = $request->input('email');
        $user->password   = Hash::make('12345678');
        $user->status     = 0;
        $user->company_id = Auth::user()->company_id;
        $user->role_id    = $request->input('role_id');
        $user->created_by = Auth::user()->id;
        try{
            $user->save();
            Toastr::success('User Created Successfully :)','Success');
            return redirect()->route('parameter_setup.user_setup.user_list.index');
        }catch(Exception $e){
            Toastr::error($e->getMessage(),'Failed');
            return redirect()->back();
        }
    }



     /**
     * Redirect to edit page with user data
     *
     */
    public function edit($id){
        $user  = User::find($id);
        $roles = Role::all();
        $data  = [
            "user"  => $user,
            "roles" => $roles
        ];
        return view('parameter-setup.user-setup.user-list.edit', $data);
    }



     /**
     * User Data Updated
     *
     */
    public function update(Request $request, $id){
      

       $user = User::find($id);


       if ($request->hasFile('image')) {

            if(!empty($user->avatar)){
                Storage::delete($user->avatar); // remove old image
            }
            

            $image      = $request->file('image');
            $fileName   = time() .uniqid(). '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(300, 200, function ($constraint) {
                $constraint->aspectRatio();                 
            });

            $img->stream(); // <-- Key point

            //dd();
            Storage::disk('local')->put('images/user-image'.'/'.$fileName, $img,);

            $image_path = "images/user-image/{$fileName}";
        }else{
            $image_path = $user->avatar;
        }

        $user->name       = $request->input('name');
        $user->phone      = $request->input('phone');
        $user->avatar     = $image_path;
        $user->email      = $request->input('email');
        $user->status     = 0;
        $user->company_id = Auth::user()->company_id;
        $user->role_id    = $request->input('role_id');
        $user->updated_by = Auth::user()->id;
        try{
            $user->save();
            Toastr::success('User Update Successfully :)','Success');
            return redirect()->route('parameter_setup.user_setup.user_list.index');
        }catch(Exception $e){
            Toastr::error($e->getMessage(),'Failed');
            return redirect()->back();
        }
    }


     /**
     * All Pending User
     *
     */
    public function pending(){
        $users = DB::table('users as u')
        ->select(
            'u.id',
            'u.name',
            'u.phone',
            'u.avatar',
            'u.email',
            'u.status',
            'r.name as role_name',
            'u.created_at',
            'u.updated_at'
        )
        ->leftJoin('roles as r','u.role_id','r.id')
        ->where('company_id', Auth::user()->company_id)
        ->where('u.status', 0)
        ->where('u.created_by', '<>', Auth::user()->id)
        ->get();
        $data = [
            "users" => $users
        ];

       return view('parameter-setup.user-setup.user-list.pending', $data);
    }


     /**
     * Authorize User
     *
     */
    public function authorizeUser(Request $request){
        if($request->has('users')){
            $users = $request->input('users');
            foreach($users as $user){
                try{
                    DB::table('users')->where('id', $user)->update([
                        "status"      => 1,
                        "approved_by" => Auth::user()->id,
                        "approved_at" => date('Y-m-d H:i:s')
                    ]);
                    $data = [
                        "status" => 200,
                        "message" => "User Authorization Successfully"
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
     * Reject User
     *
     */
    public function rejectUser(Request $request){
        if($request->has('users')){
            $users = $request->input('users');
            foreach($users as $user){
                try{
                    DB::table('users')->where('id', $user)->update([
                        "status"      => 3,
                        "approved_by" => Auth::user()->id,
                        "approved_at" => date('Y-m-d H:i:s')
                    ]);
                    $data = [
                        "status" => 200,
                        "message" => "User Reject Successfully"
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
