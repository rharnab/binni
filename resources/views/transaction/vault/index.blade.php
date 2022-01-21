@extends('layouts.app')

@section('title', 'Vault Transaction')

@push('css')
    <link href="{{ asset('assets/backend/layouts/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
@endpush

@section('breadcrumb')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        <h2>Vault Transaction</h2>
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
                <strong>Vault Transaction</strong>
            </li>
        </ol>
    </div>
</div>
@endsection


@section('content')

<div class="wrapper wrapper-content" style="margin-bottom: -30px;">
    <div class="row">
        <div class="col-sm-12">
            <a class="btn btn-primary btn-facebook text-white" href="{{ route('transaction.vault.create') }}">
                <i class="fa fa-plus-circle"> </i> Add New
            </a>
        </div>
    </div>
</div>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
        <div class="ibox ">
            <div class="ibox-title">
                <h5>All Vault Transasction</h5>
            </div>
            <div class="ibox-content">

                <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example" >
            <thead>
            <tr>
                <th>SL</th>
                <th>Account Name</th>
                <th>Transaction Type</th>
                <th>Transaction Date</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @php $sl=1; @endphp
                @foreach($vault_transactions as $vault_transaction)
                    <tr>
                        <td>{{ $sl++ }}</td>                        
                        <td>{{ $vault_transaction->account_name }}</td>
                        <td>{{ strtoupper($vault_transaction->transaction_type) }}</td>
                        <td>{{ date('Y-m-d', strtotime($vault_transaction->transaction_date)) }}</td>
                        <td>{{ number_format($vault_transaction->amount, 2) }}/=</td>
                        <td>{{ $vault_transaction->remarks }}</td>
                        
                        <td>
                            @if($vault_transaction->status == 0)
                                <p><span class="badge badge-warning">Pending</span></p>
                            @elseif($vault_transaction->status == 1)
                                <p><span class="badge badge-primary">Approved</span></p>
                            @elseif($vault_transaction->status == 3)
                                <p><span class="badge badge-danger">Rejected</span></p>
                            @endif
                        </td>
                        <td>
                            <a href="" class="btn btn-primary">
                                <i class="fa fa-pencil"> </i> 
                            </a>
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
        function deleteUser(id) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('delete-form-'+id).submit();
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
        }
    </script>
@endpush


