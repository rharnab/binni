<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="rounded-circle" src="{{ asset('assets/backend/layouts/img/profile_small.jpg')}}"/>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold">{{ Auth::user()->name }}</span>
                        <span class="text-muted text-xs block"> Setting <b class="caret"></b></span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li class="dropdown-divider"></li>
                        <li><a class="dropdown-item" ref="{{ route('logout') }}"  onclick="event.preventDefault();
                            document.getElementById('logout-form-1').submit();"
                            {{ __('Logout') }}>Logout</a>
                        </li>
                        <form id="logout-form-1" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </div>
                <div class="logo-element">
                    BINNI+
                </div>
            </li>
            <li  class="{{ (request()->is('home')) ? 'active' : '' }}">
                <a href="{{ route('home') }}"><i class="fa fa-home"></i> <span class="nav-label">Dashboard</span></a>
            </li>

           <!-- Super Admin Menu Section Start -->
            @if(Auth::user()->role_id == 1)  
                @include('layouts.menus.super_admin')                 
            @endif
            <!-- Super Admin Menu Section End -->

           
        </ul>

    </div>
</nav>


