  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      
      <li class="nav-item">
        <a class="nav-link {{ request()->segment(1) === 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
          <i class="bi bi-house-fill"></i>
          <span>Dashboard</span>
        </a>
      </li>
      
      <!-- Start of Agents Nav -->
      {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#">
          <i class="bi bi-people-fill"></i>
          <span>Agents</span>
        </a>
      </li> --}}
      <!-- End of Agents Nav -->
      
      <li class="nav-item">
        <a class="nav-link {{ request()->segment(1) === 'tasks' ? 'active' : '' }}" href="{{ route('tasks.index') }}">
          <i class="bi bi-bar-chart-steps"></i>
          <span>Task Management</span>
        </a>
      </li>
      
      @if(Gate::allows('accessRestrictedPages'))
        <li class="nav-item">
          <a class="nav-link {{ request()->segment(1) === 'uploads' ? 'active' : '' }}" href="{{ route('uploads.index') }}">
            <i class="bi bi-cloud-arrow-up-fill"></i>
            <span>Upload Management</span>
          </a>
        </li>
      @endif
      
      <li class="nav-item">
        <a class="nav-link {{ request()->segment(1) === 'documents' ? 'active' : '' }}" href="{{ route('documents.index') }}">
          <i class="bi bi-file-earmark-text-fill"></i>
          <span>Document Management</span>
        </a>
      </li>
      
      @if(Gate::allows('accessRestrictedPages'))
        <li class="nav-item">
          <a class="nav-link {{ request()->segment(1) === 'clients' ? 'active' : '' }}" href="{{ route('clients.index') }}">
            <i class="bi bi-person-lines-fill"></i>
            <span>Client Management</span>
          </a>
        </li>
      @endif
      
      @if(Gate::allows('accessRestrictedPages'))
        <li class="nav-item">
          <a class="nav-link collapsed {{ in_array(request()->segment(1), ['appointments', 'appointment-schedules', 'block-times', 'appointment-services']) ? 'active' : '' }}" data-bs-target="#appointment-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-calendar2-week-fill"></i><span>Appointments</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="appointment-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('appointments.index') }}">
                <i class="bi bi-circle"></i><span>Appointments</span>
              </a>
            </li>
            
            @if(Gate::allows('accessAdminAndManagerPages'))
              <li>          
                <a href="{{ route('appointment-schedules.index') }}">
                  <i class="bi bi-circle"></i><span>Schedules</span>
                </a>
              </li>
              
              <li>          
                <a href="{{ route('block-times.index') }}">
                  <i class="bi bi-circle"></i><span>Block Times</span>
                </a>
              </li>
              
              <li>          
                <a href="{{ route('appointment-services.index') }}">
                  <i class="bi bi-circle"></i><span>Services</span>
                </a>
              </li>
            @endif
          </ul>
        </li>
      @endif
      
      {{-- Appointment for Client Only --}}
      @if(Gate::allows('isClient'))
        <li class="nav-item">
          <a class="nav-link collapsed {{  in_array(request()->segment(1), ['appointments', 'my-appointments']) ? 'active' : '' }}" data-bs-target="#appointment-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-calendar2-week-fill"></i><span>Appointments</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="appointment-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('my-appointments') }}">
                <i class="bi bi-circle"></i><span>My Appointments</span>
              </a>
            </li>

            <li>          
              <a href="{{ route('appointments.client-booking') }}">
                <i class="bi bi-circle"></i><span>Book an Appointment</span>
              </a>
            </li>
          </ul>
        </li>
      @endif
      
      
      @if(Gate::allows('accessRestrictedPages'))
        <a class="nav-link {{ request()->segment(1) === 'access-requests' ? 'active' : '' }}" href="{{ route('access-requests.index') }}">
          <i class="bi bi-door-open-fill"></i>
          <span>
            Access Requests
            @if($accessRequestsCount !== 0)
              <span class="badge text-bg-danger rounded-pill">{{ $accessRequestsCount }}</span>
            @endif
          </span>
        </a>
      @endif
      
      @if(Gate::allows('accessRestrictedPages'))
        <li class="nav-item">
          <a class="nav-link collapsed {{ in_array(request()->segment(1), ['messages', 'blocked']) ? 'active' : '' }}" data-bs-target="#communication-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-chat-right-dots-fill"></i><span>Communication Tools</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="communication-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('messages.index') }}">
                <i class="bi bi-circle"></i>
                <span>
                  Contact Form Messages 
                  @if($unreadMessages !== 0)
                    <span class="badge text-bg-danger rounded-pill">{{ $unreadMessages }}</span>
                  @endif
                </span>
              </a>
            </li>
            <a href="{{ route('blocked.index') }}">
              <i class="bi bi-circle"></i><span>Blocked</span>
            </a>
          </ul>
        </li>
      @endif
      
      <!-- Start of Compliance and Security Nav -->
      {{-- <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#security-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-shield-shaded"></i><span>Compliance & Security</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="security-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="#">
              <i class="bi bi-circle"></i><span>Reporting & Analytics</span>
            </a>
          </li>
        </ul>
      </li> --}}
      <!-- End of Compliance and Security Nav -->

      <!-- Start of Landing Page Nav -->
      {{-- <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#landingpage-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-body-text"></i><span>Landing Page</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="landingpage-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="#">
              <i class="bi bi-circle"></i><span>Landing Page</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="bi bi-circle"></i><span>Testimonials</span>
            </a>
          </li>
        </ul>
      </li> --}}
      <!-- End of Landing Page Nav -->
      
      @if(Gate::allows('accessAdminAndManagerPages'))
        <li class="nav-item">
          <a class="nav-link collapsed {{ in_array(request()->segment(1), ['users', 'roles', 'permissions', 'audit-logs', 'login-history']) ? 'active' : '' }}" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-person-vcard-fill"></i><span>User Management</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="users-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('users.index') }}">
                <i class="bi bi-circle"></i><span>User Accounts</span>
              </a>
            </li>
            <li>
              <a href="{{ route('roles.index') }}">
                <i class="bi bi-circle"></i><span>Roles</span>
              </a>
            </li>
            <li>
              <a href="{{ route('permissions.index') }}">
                <i class="bi bi-circle"></i><span>Permissions</span>
              </a>
            </li>
            <li>
              <a href="{{ route('audit-logs.index') }}">
                <i class="bi bi-circle"></i><span>Audit Logs</span>
              </a>
            </li>
            <li>
              <a href="{{ route('login-history.index') }}">
                <i class="bi bi-circle"></i><span>Login History</span>
              </a>
            </li>
          </ul>
        </li>
      @endif
    </ul>
  </aside><!-- End Sidebar-->