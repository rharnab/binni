
    <!-- Mainly scripts -->
    <script src="{{ asset('assets/backend/layouts/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('assets/backend/layouts/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/backend/layouts/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/backend/layouts/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('assets/backend/layouts/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>



    <!-- Custom and plugin javascript -->
    <script src="{{ asset('assets/backend/layouts/js/inspinia.js')}}"></script>
    <script src="{{ asset('assets/backend/layouts/js/plugins/pace/pace.min.js')}}"></script>

    <script src="{{ asset('assets/backend/layouts/js/toastr.min.js')}}"></script>

    <!-- Jquery Validate -->
    <script src="{{ asset('assets/backend/layouts/js/plugins/validate/jquery.validate.min.js')}}"></script>

    {!! Toastr::message() !!}

    @stack('js')