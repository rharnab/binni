
<li  class="{{ (request()->is('parameter-setup/*')) ? 'active' : '' }}" >
    <a href="#"><i class="fa fa-gear (alias)"></i> 
        <span class="nav-label">Parameter Setup </span><span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level collapse">
        <li class="{{ (request()->is('parameter-setup/user-setup/*')) ? 'active' : '' }}" >
            <a href="#"><i class="fa fa-users"></i> User Setup<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
                <li class="{{ (request()->is('parameter-setup/user-setup/user-list/index')) ? 'active' : '' }}" >
                    <a href="{{ route('parameter_setup.user_setup.user_list.index') }}"><i class="fa fa-dot-circle-o"></i>User List</a>
                </li>
                <li  class="{{ (request()->is('parameter-setup/user-setup/user-list/pending')) ? 'active' : '' }}">
                    <a href="{{ route('parameter_setup.user_setup.user_list.pending') }}"><i class="fa fa-dot-circle-o"></i>User Authorize</a>
                </li>
            </ul>
        </li>
        <li class="{{ (request()->is('parameter-setup/employee-setup/*')) ? 'active' : '' }}" >
            <a href="#"><i class="fa fa-user"></i> Employee Setup<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
                <li class="{{ (request()->is('parameter-setup/employee-setup/employee-list/index')) ? 'active' : '' }}" >
                    <a href="{{ route('parameter_setup.employee_setup.employee_list.index') }}"><i class="fa fa-dot-circle-o"></i>Employee List</a>
                </li>
                <li class="{{ (request()->is('parameter-setup/employee-setup/employee-list/pending')) ? 'active' : '' }}"  >
                    <a href="{{ route('parameter_setup.employee_setup.employee_list.pending') }}"><i class="fa fa-dot-circle-o"></i>Employee Authorize</a>
                </li>
            </ul>
        </li>
        <li class="{{ (request()->is('parameter-setup/account-setup/*')) ? 'active' : '' }}" >
            <a href="#"><i class="fa fa-bank (alias)"></i> Account Setup<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
                <li class="{{ (request()->is('parameter-setup/account-setup/index')) ? 'active' : '' }}" >
                    <a href="{{ route('parameter_setup.account_setup.index') }}"><i class="fa fa-dot-circle-o"></i>Account List</a>
                </li>
                <li class="{{ (request()->is('parameter-setup/account-setup/pending')) ? 'active' : '' }}" >
                    <a href="{{ route('parameter_setup.account_setup.pending') }}"><i class="fa fa-dot-circle-o"></i>Account Authorize</a>
                </li>
            </ul>
        </li>
        <li class="{{ (request()->is('parameter-setup/predefined-gl/*')) ? 'active' : '' }}" >
            <a href="#"><i class="fa fa-sitemap"></i> Predefined Gl<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
                <li class="{{ (request()->is('parameter-setup/predefined-gl/index')) ? 'active' : '' }}" >
                    <a href="{{ route('parameter_setup.predefined_gl.index') }}"><i class="fa fa-dot-circle-o"></i>Predefined Gl Mapping</a>
                </li>
                <li class="{{ (request()->is('parameter-setup/predefined-gl/pending')) ? 'active' : '' }}" >
                    <a href="{{ route('parameter_setup.predefined_gl.pending') }}"><i class="fa fa-dot-circle-o"></i>Mapping Authorize</a>
                </li>
            </ul>
        </li>
        <li class="{{ (request()->is('parameter-setup/inventory/*')) ? 'active' : '' }}" >
            <a href="#"><i class="fa fa-shopping-cart"></i> Inventory<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
                <li class="{{ (request()->is('parameter-setup/inventory/index')) ? 'active' : '' }}" >
                    <a href="{{ route('parameter_setup.inventory.index') }}"><i class="fa fa-dot-circle-o"></i>Inventory Setup</a>
                </li>
                <li class="{{ (request()->is('parameter-setup/inventory/pending')) ? 'active' : '' }}" >
                    <a href="{{ route('parameter_setup.inventory.pending') }}"><i class="fa fa-dot-circle-o"></i>Inventory Authorize</a>
                </li>
            </ul>
        </li>
    </ul>
</li>




<li class="{{ (request()->is('transaction/*')) ? 'active' : '' }}" >
    <a href="#"><i class="fa fa-cc-mastercard"></i> 
        <span class="nav-label">Transaction </span><span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level collapse">
        <li class="{{ (request()->is('transaction/vault/*')) ? 'active' : '' }}" >
            <a href="#"><i class="fa fa-institution (alias)"></i> Vault<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
                <li class="{{ (request()->is('transaction/vault/index')) ? 'active' : '' }}" >
                    <a href="{{ route('transaction.vault.index') }}"><i class="fa fa-dot-circle-o"></i>Vault Transaction</a>
                </li>
                <li class="{{ (request()->is('transaction/vault/pending')) ? 'active' : '' }}" >
                    <a href="{{ route('transaction.vault.pending') }}"><i class="fa fa-dot-circle-o"></i>Vault Authorize</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#"><i class="fa fa-handshake-o"></i> Regular<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
                <li><a href=""><i class="fa fa-dot-circle-o"></i>Regular Transaction</a></li>
                <li><a href=""><i class="fa fa-dot-circle-o"></i>Regular Transaction Authorize</a></li>
            </ul>
        </li>
        <li>
            <a href="#"><i class="fa fa-cutlery"></i> Inventory<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
                <li><a href=""><i class="fa fa-dot-circle-o"></i>Inventory Transaction</a></li>
                <li><a href=""><i class="fa fa-dot-circle-o"></i>Inventory Authorize</a></li>
            </ul>
        </li>
    </ul>
</li>



