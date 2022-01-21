@extends('layouts.app')

@section('title', 'Pending Employee')

@push('css')
    <link href="{{ asset('assets/backend/layouts/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
@endpush

@section('breadcrumb')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        <h2>Pending Employee</h2>
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
                <strong>Pending Employee</strong>
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
                <button class="btn btn-danger btn-sm" onclick="rejectEmployee()">Decline</button> 
                ||
                <button class="btn btn-primary btn-sm" onclick="authorizeEmployee()">Authorize</button>
            </span>
        </div>
    </div>
</div>



<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
        <div class="ibox" id="loader">
            <div class="ibox-title">
                <h5>All Authorization Pending Employee List</h5>                
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
                <th>Name</th>
                <th>Phone</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Join Date</th>
                <th>Salary</th>
                <th>Resume</th>
            </tr>
            </thead>
            <tbody>
                @php
                    $sl = 1;   
                @endphp
                @foreach ($employees as $employee)
                <tr>
                    <td>
                        <input type="checkbox"  class="select_item" name="single_item" value="{{ $employee->id }}">
                    </td>
                    <td>{{ $sl++ }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->personal_phone }}</td>
                    <td>{{ $employee->department_name }}</td>
                    <td>{{ $employee->designation_name }}</td>
                    <td>{{ date('jS F,Y', strtotime($employee->join_date)) }}</td>
                    <td>{{ number_format($employee->monthly_salary ) }}</td>
                    <td style="text-align: center">
                        <a href="{{ asset('storage/app/'.$employee->resume) }}" download>
                            <i class="fa fa-download"></i>
                        </a>
                    </td>
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
        function rejectUser(){

        }
    </script>
    <script>
        function authorizeEmployee(){
            var selected_items = [];
            $("input:checkbox[name='single_item']:checked").each(function(){
                selected_items.push($(this).val());
            });
            if(selected_items.length > 0){
                swal({
                    title: 'Are you sure?',
                    text: "You want to authorize this users!",
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
                        this.authorizedEmployee(selected_items);
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


        function authorizedEmployee(employees){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type       : 'POST',
                url        : "{{ route('parameter_setup.employee_setup.employee_list.authorize')}}",
                data       : {
                    "employees" : employees
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
        function rejectEmployee(){
            var selected_items = [];
            $("input:checkbox[name='single_item']:checked").each(function(){
                selected_items.push($(this).val());
            });

           

            if(selected_items.length > 0){
                swal({
                    title: 'Are you sure?',
                    text: "You want to reject this users!",
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
                        this.rejectSelectedEmployee(selected_items);
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

        function rejectSelectedEmployee(employees){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type       : 'POST',
                url        : "{{ route('parameter_setup.employee_setup.employee_list.reject')}}",
                data       : {
                    "employees" : employees
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


