@extends('layouts.app')

@section('title')
    Create Employee
@endsection

@push('css')
    <link href="{{ asset('assets/backend/layouts/css/plugins/select2/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/layouts/css/plugins/select2/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/layouts/css/plugins/jasny/jasny-bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/layouts/css/plugins/steps/jquery.steps.css')}}" rel="stylesheet">
   <style>
        .add-new-button{
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
        }
        .wizard-big.wizard > .content {
            min-height: 379px;
        }
    </style>
@endpush


@section('breadcrumb')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Crete New Employee</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a>Parameter Setup</a>
            </li>
            <li class="breadcrumb-item">
                <a>Employee Setup</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Employee Create</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>
@endsection



@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Employee Create</h5>
                </div>
                <div class="ibox-content">
                    <h2>
                        Add New Employee
                    </h2>
                    <form id="form" enctype="multipart/form-data" action="{{ route('parameter_setup.employee_setup.employee_list.store') }}" method="POST" class="wizard-big">
                        @csrf
                        <h1>Personal</h1>
                        <fieldset>
                            <h2>Personal Information</h2>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Select User *</label>
                                        <select class="form-control"  onchange="userEmailPhone()" name="user_id" id="user_id">
                                            <option value="">Select User</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Name *</label>
                                        <input type="text" readonly class="form-control" name="name" id="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone *</label>
                                        <input type="text" readonly class="form-control" name="personal_phone" id="personal_phone" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email *</label>
                                        <input type="email" readonly class="form-control" name="personal_email" id="personal_email" required>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Blood Group *</label>
                                        <select class="form-control" required  name="blood_group_id" id="blood_group_id">
                                            <option value="">Select Blood Group</option>
                                            @foreach($blood_groups as $blood_group)
                                                <option value="{{ $blood_group->id }}">{{ $blood_group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>National Id No *</label>
                                        <input type="text" class="form-control" name="national_id_no" id="national_id_no" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Passport Id No</label>
                                        <input type="text" class="form-control" name="passport_id_no" id="passport_id_no">
                                    </div>
                                </div>
                            </div>

                        </fieldset>
                        <h1>Family</h1>
                        <fieldset>
                            <h2>Family Information</h2>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Father Name *</label>
                                        <input type="text" class="form-control" name="father_name" id="father_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Mother Name *</label>
                                        <input type="text" class="form-control" name="mother_name" id="mother_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Spouse Name (If Married)</label>
                                        <input type="text" class="form-control" name="spouse_name" id="spouse_name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Emergency Contact Person *</label>
                                        <input type="text" class="form-control" name="emergency_contact_person" id="emergency_contact_person" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Emergency Contact Person Relation</label>
                                        <input type="text" class="form-control" name="emergency_contact_person_relation" id="emergency_contact_person_relation" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Emergency Contact No</label>
                                        <input type="text" class="form-control" name="emergency_contact_no" id="emergency_contact_no" required>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h1>Address</h1>
                        <fieldset>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Current Address</label>
                                            <textarea name="current_address" id="current_address" cols="30" rows="8" class="form-control" required></textarea>
                                        </div>                                        
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Permanent Address</label>
                                            <textarea name="permanent_address" id="permanent_address" cols="30" rows="8" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                </div>
                        </fieldset>

                        <h1>Employee Setup</h1>
                        <fieldset>
                            <h2>Employee Setup</h2>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Select Department * </label>
                                        <select class="form-control" required  name="department_id" id="department_id">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Select Designation *</label>
                                        <select class="form-control" required  name="designation_id" id="designation_id">
                                            <option value="">Select Designation</option>
                                            @foreach($designations as $designation)
                                                <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Reference</label>
                                        <input id="reference" name="reference" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Resume</label>
                                        <div class="custom-file">
                                            <input id="logo" type="file" name="resume"  accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required class="custom-file-input">
                                            <label for="logo" class="custom-file-label">Choose file...</label>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Previous Working Experience</label>
                                        <input type="text" class="form-control" name="previous_working_experience" id="previous_working_experience" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Join Date</label>
                                        <input type="date" class="form-control" name="join_date" id="join_date" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Work Type</label>
                                        <select class="form-control" required  name="work_type" id="work_type">
                                            <option value="">Select Work Type</option>
                                            <option value="1">Full-Time</option>
                                            <option value="0">Part-Time</option>
                                            <option value="2">Contractual</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Monthly Salary</label>
                                        <input type="text" class="form-control" name="monthly_salary" id="monthly_salary">
                                    </div>
                                    
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <!-- Select2 -->
    <script src="{{ asset('assets/backend/layouts/js/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{ asset('assets/backend/layouts/js/plugins/steps/jquery.steps.min.js')}}"></script>
    <script>
        $(".select2").select2({
            theme: 'bootstrap4'
        });
        $(document).ready(function(){
            $("#wizard").steps();
            $("#form").steps({
                bodyTag: "fieldset",
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    // Always allow going backward even if the current step contains invalid fields!
                    if (currentIndex > newIndex)
                    {
                        return true;
                    }

                    // Forbid suppressing "Warning" step if the user is to young
                    if (newIndex === 3 && Number($("#age").val()) < 18)
                    {
                        return false;
                    }

                    var form = $(this);

                    // Clean up if user went backward before
                    if (currentIndex < newIndex)
                    {
                        // To remove error styles
                        $(".body:eq(" + newIndex + ") label.error", form).remove();
                        $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
                    }

                    // Disable validation on fields that are disabled or hidden.
                    form.validate().settings.ignore = ":disabled,:hidden";

                    // Start validation; Prevent going forward if false
                    return form.valid();
                },
                onStepChanged: function (event, currentIndex, priorIndex)
                {
                    // Suppress (skip) "Warning" step if the user is old enough.
                    if (currentIndex === 2 && Number($("#age").val()) >= 18)
                    {
                        $(this).steps("next");
                    }

                    // Suppress (skip) "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 2 && priorIndex === 3)
                    {
                        $(this).steps("previous");
                    }
                },
                onFinishing: function (event, currentIndex)
                {
                    var form = $(this);

                    // Disable validation on fields that are disabled.
                    // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
                    form.validate().settings.ignore = ":disabled";

                    // Start validation; Prevent form submission if false
                    return form.valid();
                },
                onFinished: function (event, currentIndex)
                {
                    var form = $(this);

                    // Submit form input
                    form.submit();
                }
            }).validate({
                        errorPlacement: function (error, element)
                        {
                            element.before(error);
                        },
                        rules: {
                            confirm: {
                                equalTo: "#password"
                            }
                        }
                    });
       });
    </script>
    <script>
        function userEmailPhone(){
            var user_id = $('#user_id').val();
            if(user_id != ''){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url : "{{ route('parameter_setup.employee_setup.employee_list.employee_info') }}",
                    data: {
                        "user_id": user_id
                    },
                    success    : (data) => {
                       var obj = JSON.parse(data);
                       if(obj.status === 200){
                           $('#name').val(obj.data.name.trim());
                           $('#personal_email').val(obj.data.email.trim());
                           $('#personal_phone').val(obj.data.phone.trim());
                       }else{
                            $('#name').val('');
                            $('#personal_email').val('');
                            $('#personal_phone').val('');
                       }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        }
    </script>
@endpush