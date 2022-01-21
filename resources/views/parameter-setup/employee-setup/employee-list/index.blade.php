@extends('layouts.app')

@section('title', 'Employee List')

@push('css')
    <link href="{{ asset('assets/backend/layouts/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
@endpush

@section('breadcrumb')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        <h2>Employee Setup</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                Parameter Setup
            </li>
            <li class="breadcrumb-item">
                Employee Setup
            </li>
            <li class="breadcrumb-item active">
                <strong>Employee  List</strong>
            </li>
        </ol>
    </div>
</div>
@endsection


@section('content')

<div class="wrapper wrapper-content" style="margin-bottom: -30px;">
    <div class="row">
        <div class="col-sm-12">
            <a class="btn btn-primary btn-facebook text-white" href="{{ route('parameter_setup.employee_setup.employee_list.create') }}">
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
                <h5>All Employee List</h5>
            </div>
            <div class="ibox-content">

                <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example" >
            <thead>
            <tr>
                <th>SL</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Join Date</th>
                <th>Salary</th>
                <th>Resume</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $sl = 1;   
            @endphp
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $sl++ }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->personal_phone }}</td>
                    <td>{{ $employee->department_name }}</td>
                    <td>{{ $employee->designation_name }}</td>
                    <td>{{ date('jS F,Y', strtotime($employee->join_date)) }}</td>
                    <td style="text-align: center">
                        <a href="{{ asset('storage/app/'.$employee->resume) }}" download>
                            <i class="fa fa-download"></i>
                        </a>
                    </td>
                    <td>
                        @if($employee->status == 0)
                            <p><span class="badge badge-danger">Pending</span></p>
                        @elseif($employee->status == 1)
                            <p><span class="badge badge-primary">Approved</span></p>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('parameter_setup.employee_setup.employee_list.edit', [$employee->id]) }}" class="btn btn-primary">
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


