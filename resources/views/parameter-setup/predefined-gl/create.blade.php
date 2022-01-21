@extends('layouts.app')

@section('title')
    Predefined GL Mapping
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
        <h2>Add New Predefined GL</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a>Parameter Setup</a>
            </li>
            <li class="breadcrumb-item">
                <a>Predefined Gl</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Add Predefined GL Mapping</strong>
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
                <h2 class="font-bold">Add Predefined Gl Mapping</h2>
                <div class="row">
                    <div class="col-lg-12">

                        <form id="user-create-form" method="POST" enctype="multipart/form-data" action="{{ route('parameter_setup.predefined_gl.store') }}">
                            @csrf
                            
                           <div class="form-group">
                                <label>Predefined GL Type</label>
                                <select class="select2 form-control @error('predefined_gl_id') is-invalid @enderror" required name="predefined_gl_id" >
                                    <option value="">Select User Role</option>
                                   @foreach($predefined_accounts as $predefined_account)
                                        <option value="{{ $predefined_account->id }}">{{ $predefined_account->name }}</option>
                                   @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label>Select Mapping Account</label>
                                <select class="select2 form-control @error('account_id') is-invalid @enderror" required name="account_id" >
                                    <option value="">Select Mapping Account</option>
                                   @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                   @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Transaction Type</label>
                                <select class="select2 form-control @error('transaction_type') is-invalid @enderror" required name="transaction_type" >
                                    <option value="">Select Transaction Type</option>
                                    <option value="dr">Dr.</option>
                                    <option value="cr">Cr.</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea name="remarks" id="" cols="30" rows="3" class="form-control" required ></textarea>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-offset-4 col-lg-8">
                                    <button class="btn btn-sm btn-primary" type="submit">Submit</button>
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