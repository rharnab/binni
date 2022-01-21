@extends('layouts.app')

@section('title')
    Add New Inventory
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
                <a>Inventory Setup</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Add New Inventory</strong>
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
                <h2 class="font-bold">Add New Inventory</h2>
                <div class="row">
                    <div class="col-lg-12">

                        <form id="user-create-form" method="POST" enctype="multipart/form-data" action="{{ route('parameter_setup.inventory.store') }}">
                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"> 
                                @if($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif 
                            </div>

                            <div class="form-group">
                                <label>Short Description</label>
                                <textarea name="short_description" id="short_description" cols="30" rows="1" class="form-control"></textarea>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" id="description" cols="30" rows="2" class="form-control"></textarea>
                            </div>

                            <div class="form-group">
                                <label>Image</label>
                                <div class="custom-file">
                                    <input id="logo" type="file" name="image"   accept="image/x-png,image/gif,image/jpeg"  class="custom-file-input">
                                    <label for="logo" class="custom-file-label">Choose file...</label>
                                </div> 
                            </div>

                            <div class="form-group">
                                <label>Asset Type</label> <br>
                                <select class="form-control" required  name="asset_type" id="asset_type">
                                    <option value="">Select Asset Type</option>
                                    <option value="0">Office Accessories</option>
                                    <option value="1">Fixed Asset</option>
                                </select>
                            </div>

                            <div class="form-group" id="depreciation_section">
                                <label>Depreciation Value</label>
                                <input type="text" name="depreciation_value" id="depreciation_value" class="form-control">
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