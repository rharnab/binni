@extends('layouts.app')

@section('title')
    Edit User
@endsection

@push('css')
    <link href="{{ asset('assets/backend/layouts/css/plugins/select2/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/layouts/css/plugins/select2/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/layouts/css/plugins/jasny/jasny-bootstrap.min.css')}}" rel="stylesheet">
    <style>
        .add-new-button{
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
        }
    </style>
@endpush


@section('breadcrumb')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Crete New User</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a>Parameter Setup</a>
            </li>
            <li class="breadcrumb-item">
                <a>User Setup</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>User Modify</strong>
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
        <div class="col-md-6">
            <div class="ibox-content">
                <h2 class="font-bold">Modify User Information</h2>
                <div class="row">
                    <div class="col-lg-12">

                        <form id="user-create-form" method="POST" enctype="multipart/form-data" action="{{ route('parameter_setup.user_setup.user_list.update', [$user->id]) }}">
                            @csrf
                            
                           <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $user->name }}"> 
                                    @if($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif   
                            </div>


                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $user->email }}">
                                    @if($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif 
                            </div>

                            <div class="form-group">
                                <label>Mobile</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ $user->phone }}">
                                @if($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Role</label>
                                <select class="select2 form-control @error('role_id') is-invalid @enderror" name="role_id" >
                                    <option value="">Select User Role</option>
                                   @foreach($roles as $role)
                                        <option value="{{ $role->id }}" @if($user->role_id == $role->id) {{ "selected" }}  @endif >{{ $role->name }}</option>
                                   @endforeach
                                </select>
                                @if($errors->has('role_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('role_id') }}</strong>
                                    </span>
                                @endif                                 
                            </div>

                            <div class="form-group">
                                <label>Upload User Image</label> <br>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <span class="btn btn-default btn-file"><span class="fileinput-new">Select file</span>
                                    <span class="fileinput-exists">Change</span>
                                    <input type="file" name="image" id="image"/></span>
                                    <span class="fileinput-filename"></span>
                                    <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                                </div> 
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-offset-4 col-lg-8">
                                    <button class="btn btn-sm btn-primary" type="submit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <!-- Select2 -->
    <script src="{{ asset('assets/backend/layouts/js/plugins/select2/select2.full.min.js')}}"></script>
    <script>
        $(".select2").select2({
            theme: 'bootstrap4'
        });
    </script>
      <script>
        $(function() {

        $.validator.setDefaults({
            errorClass: 'help-block',
            highlight: function(element) {
                $(element)
                .closest('.form-group')
                .addClass('has-error');
            },
            unhighlight: function(element) {
                $(element)
                .closest('.form-group')
                .removeClass('has-error');
            },
            errorPlacement: function (error, element) {
                if (element.prop('type') === 'checkbox') {
                error.insertAfter(element.parent());
                } else {
                error.insertAfter(element);
                }
            }
            });

           

            $("#user-create-form").validate({
                rules: {
                    user_id: {
                        required: true                        
                    },
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email : true,
                    },
                    phone: {
                        required: true
                    },
                    role_id: {
                        required: true
                    },
                    branch_id: {
                        required: true,
                    }
                },
                messages: {
                    user_id: {
                        required: "please write user id"                        
                    },
                    name: {
                        required: "please write user name"
                    },
                    email: {
                        required: "please write user email",
                        email : "please enter valid email",
                    },
                    phone: {
                        required: "please enter user mobile number"
                    },
                    role_id: {
                        required: "please select user role"
                    },
                    branch_id: {
                        required: "please select user branch",
                    }
                }
            });

        });
    </script>
@endpush