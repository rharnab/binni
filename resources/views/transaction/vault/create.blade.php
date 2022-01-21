@extends('layouts.app')

@section('title')
    Add New Vault Transaction
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
        <h2>Add New Vault Transaction</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a>Transaction</a>
            </li>
            <li class="breadcrumb-item">
                <a>Vault</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Add New Vault Transaction</strong>
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
                <h2 class="font-bold">Add New Vault Transaction</h2>
                <div class="row">
                    <div class="col-lg-12">
                        <form id="user-create-form" method="POST" enctype="multipart/form-data" action="{{ route('transaction.vault.store') }}">
                            @csrf
                            <div class="form-group">
                                <label>Select Vault Account</label> <br>
                                <select class="form-control" required name="voult_account_id" id="voult_account_id">
                                    <option value="">Select Account</option>
                                    <option value="{{ $account_info->account_id }}" selected>{{ $account_info->account_name }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Select Transaction Type</label> <br>
                                <select class="form-control" required  name="transaction_type" id="transaction_type">
                                    <option value="">Select Transaction Type</option>
                                    @if($account_info->transaction_type == 'cr')
                                        <option value="cr" selected>CR</option>
                                    @elseif($account_info->transaction_type == 'cr')
                                        <option value="dr" selected>DR</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Transaction Date</label>
                                <input type="date" name="transaction_date" id="transaction_date" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" name="amount" id="amount" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea name="remarks" id="remarks" cols="30" rows="3" class="form-control" required></textarea>
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
        $('#depreciation_section').hide();
        $('#asset_type').on('change',function(){
            var asset_type = $('#asset_type').val();
            if(asset_type == 1){
                $('#depreciation_section').show();
                $('#depreciation_value').attr('required', true);
            }else{
                $('#depreciation_section').hide();
                $('#depreciation_value').attr('required', true);
            }
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

           

            $("#user-create-form").validate();

        });
    </script>
@endpush