<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="dropdown"  align="center">
                <button type="button" class="btn waves-effect" id="page-header-user-dropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <p style="font-weight: bold"> {{\Illuminate\Support\Facades\Auth::user()->name}} </p>
                </button>
                <div class="dropdown-menu">
                    <!-- item-->
                    <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle font-size-17 align-middle mr-1"></i> @lang('translation.Profile')</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-power-off font-size-17 align-middle mr-1 text-danger"></i>
                        @lang('translation.Logout')
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">@lang('translation.Main')</li>
                <li>
                    <a href="/" class="waves-effect">
                        <i class="ti-home"></i><span class="badge badge-pill badge-primary float-right">2</span>
                        <span>@lang('translation.Dashboard')</span>
                    </a>
                </li>



                <li class="menu-title">Wallpaper</li>

                <li>
                    <a href="{{route('tags.index')}}" >
                        <i class="fas fa-tag"></i>
                        <span> Tags </span>
                    </a>
                    <a href="{{route('wallpapers.index')}}" >
                        <i class="ti-image"></i>
                        <span> Wallpaper </span>
                    </a>

                    <a href="{{route('sites.index')}}" >
                        <i class="ti-world"></i>
                        <span> Sites </span>
                    </a>
                    <a href="{{route('apikeys.index')}}" >
                        <i class="ti-key"></i>
                        <span> Api Keys </span>
                    </a>
                    <a href="{{route('blockips.index')}}" >
                        <i class="ti-na"></i>
                        <span> Block IPs </span>
                    </a>

                </li>


                <li class="menu-title">@lang('translation.Extras')</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ti-user"></i>
                        <span> Quản lý tài khoản </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('users.index')}}">User</a></li>
                        <li><a href="{{route('roles_permissions.index')}}">Roles & Permissions</a></li>

                    </ul>
                </li>





            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
