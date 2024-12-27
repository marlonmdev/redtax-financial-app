<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('dashboard') }}" hx-get="{{ route('dashboard') }}" hx-target="body" class="logo text-danger fw-bold">
                <img src="{{ asset('assets/images/red-tax-logo.png') }}" alt="navbar brand" class="navbar-brand" width="180" height="auto" alt="RedTax Financial Services Logo">
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ request()->segment(1) === 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item {{ in_array(request()->segment(1), ['clients', 'client-histories']) ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#clientLinks">
                        <i class="fas fa-user-tie"></i>
                        <p>Client Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="clientLinks">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('clients.index') }}">
                                    <span class="sub-item">Client Profiles</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('history.index') }}">
                                    <span class="sub-item">Client History</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="fas fa-list-alt"></i>
                        <p>Task Management</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="fas fa-file-alt"></i>
                        <p>Documents</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#communicationToolLinks">
                        <i class="fas fa-comment-dots"></i>
                        <p>Communication Tools</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="communicationToolLinks">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="#">
                                    <span class="sub-item">Emails</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Appointments</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Messages</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="far fa-chart-bar"></i>
                        <p>Reporting & Analytics</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#complianceSecurityLinks">
                        <i class="fas fa-lock"></i>
                        <p>Compliance & Security</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="complianceSecurityLinks">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="#">
                                    <span class="sub-item">Audit Logs</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Access Controls</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>


                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#landingPageLinks">
                        <i class="fas fa-newspaper"></i>
                        <p>Landing Page</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="landingPageLinks">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="#">
                                    <span class="sub-item">Landing Page</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Testimonials</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="fas fa-users"></i>
                        <p>Agents</p>
                    </a>
                </li>

                <li class="nav-item {{ in_array(request()->segment(1), ['users', 'roles', 'permissions']) ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#userManagementLinks">
                        <i class="fas fa-user-cog"></i>
                        <p>User Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="userManagementLinks">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('users.index') }}">
                                    <span class="sub-item">User Accounts</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('roles.index') }}">
                                    <span class="sub-item">Roles</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('permissions.index') }}">
                                    <span class="sub-item">Permissions</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Audit Logs</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Login History</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->