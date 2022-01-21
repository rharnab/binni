@extends('layouts.app')

@section('title')
    Create Account
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
        <h2>Crete New Account</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a>Parameter Setup</a>
            </li>
            <li class="breadcrumb-item">
                <a>Account Setup</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Account Create</strong>
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
                <h2 class="font-bold">Create New Account</h2>
                <div class="row">
                    <div class="col-lg-12">

                        <form id="user-create-form" method="POST" enctype="multipart/form-data" action="{{ route('parameter_setup.account_setup.store') }}">
                            @csrf
                            
                           <div class="form-group">
                                <label>Account Name</label>
                                <input type="text" name="name" id="name" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Account Level</label> <br>
                                <select class="form-control" required onchange="findParentAccountNo()" name="acc_level" id="acc_level">
                                    <option value="">Select Account Level</option>
                                    @for($i=1; $i<=7; $i++)
                                        <option value="{{$i}}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Account Type</label> <br>
                                <select class="form-control" required onchange="findParentAccountNo()" name="acc_types" id="acc_types">
                                    <option value="">Select Account Types</option>
                                    @foreach($account_types as $account_type)
                                        <option value="{{ $account_type->id }}">{{ $account_type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="parent_acc">
                                <label>Parent Account</label> <br>
                                <select class="form-control" name="immidiate_parent" id="immidiate_parent">
                                    <option value="">Select Parent Account</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Allow Manual Transaction</label> <br>
                                <select class="form-control" name="allow_manual_transction" id="allow_manual_transction">
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Allow Negetive Balance</label> <br>
                                <select class="form-control" name="allow_negetive_transction" id="allow_negetive_transction">
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                </select>
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

            $("#user-create-form").validate({   });
        });
    </script>

    <script>
        
        /***************
        /*  Imadiet Parent Show
        ***************/
        function findParentAccountNo(){
            var acc_level = $('#acc_level').val();
            var acc_types = $('#acc_types').val();
            if(acc_level == 1){
                $('#parent_acc').hide();
                $('select#immidiate_parent').attr('required',false);
            }else{
                $('#parent_acc').show();
                $('select#immidiate_parent').attr('required',true);
            }
            $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              })
            if(acc_level && acc_types){
                $.ajax({
                    type: 'POST',
                    url : "{{ route('parameter_setup.account_setup.search_parent_account') }}",
                    data: {
                        "acc_level": acc_level,
                        "acc_types": acc_types
                    },
                    success    : (data) => {
                        console.log(data);
                        $('#immidiate_parent').empty().append(data);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        }
    </script>
@endpush