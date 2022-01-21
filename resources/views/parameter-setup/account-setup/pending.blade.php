@extends('layouts.app')

@section('title', 'Pending Account')

@push('css')
    <link href="{{ asset('assets/backend/layouts/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
@endpush

@section('breadcrumb')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        <h2>Account Setup</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                Parameter Setup
            </li>
            <li class="breadcrumb-item">
                Account Setup
            </li>
            <li class="breadcrumb-item active">
                <strong>Pending Account</strong>
            </li>
        </ol>
    </div>
</div>
@endsection


@section('content')


<div class="wrapper wrapper-content" style="margin-bottom: -30px;">
    <div class="row">
        <div class="col-sm-12">
            <span class="pull-right" >
                <button class="btn btn-danger btn-sm" onclick="rejectAccount()">Decline</button> 
                ||
                <button class="btn btn-primary btn-sm" onclick="authorizeAccount()">Authorize</button>
            </span>
        </div>
    </div>
</div>



<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
        <div class="ibox" id="loader">
            <div class="ibox-title">
                <h5>All Authorization Pending Account List</h5>                
            </div>
            <div class="ibox-content">
                <div class="sk-spinner sk-spinner-double-bounce">
                    <div class="sk-double-bounce1"></div>
                    <div class="sk-double-bounce2"></div>
                </div>

                <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example" >
            <thead>
            <tr>
                <th>
                    <input type="checkbox" id="checkAll">
                </th>
                <th>SL</th>
                <th>Account Name</th>
                <th>Account Code</th>
                <th>A. Level</th>
                <th>Account Types</th>
                <th>Immediate Parent</th>
                <th>A.M. Transaction</th>
                <th>A.N. Balance</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
                @php $sl=1; @endphp
                @foreach($accounts as $account)
                    <tr>
                        <td>
                            <input type="checkbox"  class="select_item" name="single_item" value="{{ $account->id }}">
                        </td>
                        <td>{{ $sl++ }}</td>
                        <td>{{ $account->name }}</td>
                        <td>{{ $account->acc_code }}</td>
                        <td>{{ $account->account_level }}</td>
                        <td>
                            {{ $account->account_type_name }}
                        </td>
                        <td>
                            @if($account->immediate_parent == 0)
                                Root Account
                            @else 
                                {{ $account->parent_accont_name }}
                            @endif
                            
                        </td>
                        <td>
                            @if($account->allow_manual_transaction == 1)
                                Yes
                            @elseif($account->allow_manual_transaction == 0)
                                No
                            @endif
                        </td>
                        <td>
                            @if($account->allow_negative_balance == 1)
                                Yes
                            @elseif($account->allow_negative_balance == 0)
                                No
                            @endif
                        </td>
                        <td>
                           <p><span class="badge badge-danger">Pending</span></p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
                </div>

            </div>
        </div>
    </div>
    </div>
</div>




@endsection

@push('js')
    <script src="{{ asset('assets/backend/layouts/js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{ asset('assets/backend/layouts/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('assets/backend/layouts/js/sweetalert2.all.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });

        });
    </script>
    <script>
        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
    <script>
        function authorizeAccount(){
            var selected_items = [];
            $("input:checkbox[name='single_item']:checked").each(function(){
                selected_items.push($(this).val());
            });
            if(selected_items.length > 0){
                swal({
                    title: 'Are you sure?',
                    text: "You want to authorize this account!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, authorize it!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        event.preventDefault();
                        this.authorizeSelectedAccount(selected_items);
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {
                        swal(
                            'Cancelled',
                            'Your data is safe :)',
                            'error'
                        )
                    }
                })
            }else{

            }
        }


        function authorizeSelectedAccount(accounts){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type       : 'POST',
                url        : "{{ route('parameter_setup.account_setup.authorize')}}",
                data       : {
                    "accounts" : accounts
                },
                beforeSend: function() {
                    $('#loader').children('.ibox-content').addClass('sk-loading');
                },
                success    : (data) => {
                    var obj = JSON.parse(data);
                    alert(obj.message);
                    location.reload();
                },
                error: function(data) {
                    alert(data);
                },
                complete: function() {
                    $('#loader').children('.ibox-content').removeClass('sk-loading');
                }
            });  
            
        }
    </script>

    <script>
        function rejectAccount(){
            var selected_items = [];
            $("input:checkbox[name='single_item']:checked").each(function(){
                selected_items.push($(this).val());
            });
            if(selected_items.length > 0){
                swal({
                    title: 'Are you sure?',
                    text: "You want to reject this account!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reject it!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        event.preventDefault();
                        this.rejectSelectedAccount(selected_items);
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {
                        swal(
                            'Cancelled',
                            'Your data is safe :)',
                            'error'
                        )
                    }
                })
            }else{

            }
        }

        function rejectSelectedAccount(accounts){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type       : 'POST',
                url        : "{{ route('parameter_setup.account_setup.reject')}}",
                data       : {
                    "accounts" : accounts
                },
                beforeSend: function() {
                    $('#loader').children('.ibox-content').addClass('sk-loading');
                },
                success    : (data) => {
                    var obj = JSON.parse(data);
                    alert(obj.message);
                    location.reload();
                },
                error: function(data) {
                    alert(data);
                },
                complete: function() {
                    $('#loader').children('.ibox-content').removeClass('sk-loading');
                }
            });  
        }

    </script>
@endpush


