@include('layouts.partial._header')
    <body>
        
        <div id="wrapper">
            @include('layouts.partial._sidebar')
            <div id="page-wrapper" class="gray-bg">
                @include('layouts.partial._topbar')
                @yield('breadcrumb')
                @yield('content')
            </div>
        </div>

        @include('layouts.partial._footer')

    </body>
</html>
