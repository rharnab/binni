<?php

namespace App\Http\Controllers\ParameterSetup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Check Authencticate user
     *
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show ALl Employee List
     *
     */
    public function index(){
        $employees = DB::table('employees as e')
        ->select(
            'e.id',
            'e.name',
            'e.personal_phone',
            'e.join_date',
            'e.resume',
            'e.status',
            'e.monthly_salary',
            'd.name as department_name',
            'ds.name as designation_name',
        )
        ->leftJoin('departments as d', 'e.department_id', 'd.id')
        ->leftJoin('designations as ds', 'e.designation_id', 'ds.id')
        ->where('e.company_id', Auth::user()->company_id)
        ->get();
        $data = [
            "employees" => $employees
        ];
       return view('parameter-setup.employee-setup.employee-list.index', $data);
    }


     /**
     * Redirect to customer create page
     *
     */
    public function create(){
        $users        = DB::table('users')->select('id','name')->where('company_id', Auth::user()->company_id)->where('status', 1)->get();
        $departments  = DB::table('departments')->select('id','name')->where('company_id', Auth::user()->company_id)->where('status', 1)->get();
        $designations = DB::table('designations')->select('id','name')->where('company_id', Auth::user()->company_id)->where('status', 1)->get();
        $blood_groups = DB::table('blood_groups')->select('id','name')->get();
        $data         = [
            "users"        => $users,
            "departments"  => $departments,
            "designations" => $designations,
            "blood_groups" => $blood_groups,
        ];
        return view('parameter-setup.employee-setup.employee-list.create', $data);
    }


     /**
     * Employee Information
     *
     */
    public function employeeInfo(Request $request){
        if($request->has('user_id') && !empty($request->has('user_id'))){
            $user_id = $request->input('user_id');
            $data    = DB::table('users')->select('name', 'email', 'phone')->where('company_id', Auth::user()->company_id)->where('id', $user_id)->first();
            $response = [
                "status" => 200,
                "data"   => [
                    "name"  => $data->name,
                    "email" => $data->email,
                    "phone" => $data->phone
                ]
            ];
            return json_encode($response);
        }else{
            $response = [
                "status" => 404
            ];
            return json_encode($response);
        }
    }


    /**
     * Employee Information Store
     *
     */
    public function store(Request $request){
        

        if($request->has('spouse_name') && !empty($request->input('spouse_name'))){
            $is_married = 1;
        }else{
            $is_married = 0;
        }

        if ($request->hasFile('resume')) {
            $resume_path =  $request->file('resume')->store('resume');
        }else{
            $resume_path = "";
        }

        

        $employee_info = DB::table('employees')->select('id')->where('user_id', $request->input('user_id'))->where('company_id', Auth::user()->company_id)->first();
        if( isset($employee_info->id)  && !empty($employee_info->id) ){
            $employee = Employee::find($employee_info->id);
        }else{
            $employee = new Employee();           
        }


        $employee->company_id                        = Auth::user()->company_id;
        $employee->user_id                           = $request->input('user_id');
        $employee->designation_id                    = $request->input('designation_id');
        $employee->blood_group_id                    = $request->input('blood_group_id');
        $employee->department_id                     = $request->input('department_id');
        $employee->name                              = $request->input('name');
        $employee->father_name                       = $request->input('father_name');
        $employee->mother_name                       = $request->input('mother_name');
        $employee->is_merried                        = $is_married;
        $employee->spouse_name                       = $request->input('spouse_name');
        $employee->personal_phone                    = $request->input('personal_phone');
        $employee->official_phone                    = '';
        $employee->current_address                   = $request->input('current_address');
        $employee->permanent_address                 = $request->input('permanent_address');
        $employee->reference                         = $request->input('reference');
        $employee->national_id_no                    = $request->input('national_id_no');
        $employee->passport_id_no                    = $request->input('passport_id_no');
        $employee->emergency_contact_person          = $request->input('emergency_contact_person');
        $employee->emergency_contact_person_relation = $request->input('emergency_contact_person_relation');
        $employee->emergency_contact_number          = $request->input('emergency_contact_no');
        $employee->previous_working_experience       = $request->input('previous_working_experience');
        $employee->join_date                         = date('Y-m-d', strtotime($request->input('join_date')));
        $employee->monthly_salary                    = $request->input('monthly_salary');
        $employee->work_type                         = $request->input('work_type');
        $employee->resume                            = $resume_path;
        $employee->photo                             = '';
        $employee->status                            = 0;
        $employee->created_by                        = Auth::user()->id;

        try{
            $employee->save();
            Toastr::success('Employee Setup Successfully :)','Success');
            return redirect()->route('parameter_setup.employee_setup.employee_list.index');
        }catch(Exception $e){
            Toastr::error($e->getMessage(),'Failed');
            return redirect()->back();  
        }
    }


    /**
     * Employee Information With Edit Page
     *
     */
    public function edit($id){
        return $id;
    }


    /**
     * Pending Employee List
     *
     */
    public function pending(){
        $employees = DB::table('employees as e')
        ->select(
            'e.id',
            'e.name',
            'e.personal_phone',
            'e.join_date',
            'e.resume',
            'e.status',
            'e.monthly_salary',
            'd.name as department_name',
            'ds.name as designation_name',
        )
        ->leftJoin('departments as d', 'e.department_id', 'd.id')
        ->leftJoin('designations as ds', 'e.designation_id', 'ds.id')
        ->where('e.company_id', Auth::user()->company_id)
        ->where('e.status', 0)
        ->get();
        $data = [
            "employees" => $employees
        ];
        return view('parameter-setup.employee-setup.employee-list.pending', $data);
    }

    /**
     * Employee Authorization
     *
     */
    public function authorizeEmployee(Request $request){
        if($request->has('employees')){
            $employees = $request->input('employees');
            foreach($employees as $employee){
                try{
                    DB::table('employees')->where('id', $employee)->update([
                        "status"      => 1,
                        "approved_by" => Auth::user()->id,
                        "approved_at" => date('Y-m-d H:i:s')
                    ]);
                    $data = [
                        "status" => 200,
                        "message" => "Employee Authorization Successfully"
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


    public function rejectEmployee(Request $request){
        if($request->has('employees')){
            $employees = $request->input('employees');
            foreach($employees as $employee){
                try{
                    DB::table('employees')->where('id', $employee)->update([
                        "status"      => 3,
                        "approved_by" => Auth::user()->id,
                        "approved_at" => date('Y-m-d H:i:s')
                    ]);
                    $data = [
                        "status" => 200,
                        "message" => "Employee Reject Successfully"
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
