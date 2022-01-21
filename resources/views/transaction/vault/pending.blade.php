@extends('layouts.app')

@section('title', 'Pending Vault Transaction')

@push('css')
    <link href="{{ asset('assets/backend/layouts/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
@endpush

@section('breadcrumb')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        <h2>Pending Vault Transaction</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                Transaction
            </li>
            <li class="breadcrumb-item">
                Vault
            </li>
            <li class="breadcrumb-item active">
                <strong>Vault Transaction Auhtorize</strong>
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
                <button class="btn btn-danger btn-sm" onclick="rejectVaultTransaction()">Decline</button> 
                ||
                <button class="btn btn-primary btn-sm" onclick="authorizeVaultTransaction()">Authorize</button>
            </span>
        </div>
    </div>
</div>



<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
        <div class="ibox" id="loader">
            <div class="ibox-title">
                <h5>All Authorization Pending Vault Transaction List</h5>                
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
                <th>Transaction Type</th>
                <th>Transaction Date</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Request Time</th>
            </tr>
            </thead>
            <tbody>
                @php $sl=1; @endphp
                @foreach($vault_transactions as $vault_transaction)
                    <tr>
                        <td>
                            <input type="checkbox"  class="select_item" name="single_item" value="{{ $vault_transaction->id }}">
                        </td>
                        <td>{{ $sl++ }}</td>                        
                        <td>{{ $vault_transaction->account_name }}</td>
                        <td>{{ strtoupper($vault_transaction->transaction_type) }}</td>
                        <td>{{ date('Y-m-d', strtotime($vault_transaction->transaction_date)) }}</td>
                        <td>{{ number_format($vault_transaction->amount, 2) }}/=</td>
                        <td>{{ $vault_transaction->remarks }}</td>
                        <td>{{ date('Y-m-d h:i a', strtotime($vault_transaction->created_at)) }}</td>
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
        function rejectUser(){

        }
    </script>
    <script>
        function authorizeVaultTransaction(){
            var selected_items = [];
            $("input:checkbox[name='single_item']:checked").each(function(){
                selected_items.push($(this).val());
            });
            if(selected_items.length > 0){
                swal({
                    title: 'Are you sure?',
                    text: "You want to authorize this vault transaction!",
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
                        this.authorizeSelectedVaultTransaction(selected_items);
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


        function authorizeSelectedVaultTransaction(vault_transactions){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type       : 'POST',
                url        : "{{ route('transaction.vault.authorize')}}",
                data       : {
                    "vault_transactions" : vault_transactions
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
        function rejectVaultTransaction(){
            var selected_items = [];
            $("input:checkbox[name='single_item']:checked").each(function(){
                selected_items.push($(this).val());
            });
            if(selected_items.length > 0){
                swal({
                    title: 'Are you sure?',
                    text: "You want to reject this vault transactioin!",
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
                        this.rejectSelectedVaultTransaction(selected_items);
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

        function rejectSelectedVaultTransaction(vault_transactions){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type       : 'POST',
                url        : "{{ route('transaction.vault.reject')}}",
                data       : {
                    "vault_transactions" : vault_transactions
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


