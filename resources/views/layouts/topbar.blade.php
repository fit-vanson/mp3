<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex" >
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{route('admin.home')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <hr>
                        <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="80">
                    </span>
                </a>

{{--                <div class="dropdown">--}}
{{--                    <button type="button" class="btn waves-effect" id="page-header-user-dropdown"--}}
{{--                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                        <img class="rounded-circle header-profile-user" style="width: 100px; height: 100px"  src="{{ URL::asset('/assets/images/users/user-4.jpg') }}"--}}
{{--                             alt="Header Avatar">--}}
{{--                    </button>--}}
{{--                    <div class="dropdown-menu dropdown-menu-right">--}}
{{--                        <!-- item-->--}}
{{--                        <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle font-size-17 align-middle mr-1"></i> @lang('translation.Profile')</a>--}}
{{--                        <div class="dropdown-divider"></div>--}}
{{--                        <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">--}}
{{--                            <i class="bx bx-power-off font-size-17 align-middle mr-1 text-danger"></i>--}}
{{--                            @lang('translation.Logout')--}}
{{--                        </a>--}}
{{--                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
{{--                            @csrf--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>



            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="mdi mdi-menu"></i>
            </button>

            <div class="d-none d-sm-block" >
                    <div class="page-title-box" >
                        <h4 class="font-size-18">{{@$page_title}}</h4>
                    </div>
            </div>
        </div>

        <div class="d-flex">
            <div class="d-none d-sm-block">
                <div class="d-inline-block"style="padding-right: 30px;">
                    @if(isset($page_title))
                    <button type="button" class="btn btn-primary waves-effect waves-light create{{preg_replace('/\s+/','',$page_title)}}">Create</button>
                    @endif
{{--                    <a class="btn btn-primary" href="#" role="button" id="create{{preg_replace('/\s+/','',$page_title)}}">Create</a>--}}
                </div>
            </div>
{{--            <div class="dropdown d-inline-block">--}}
{{--                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"--}}
{{--                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                    <img class="rounded-circle header-profile-user" src="{{ URL::asset('/assets/images/users/user-4.jpg') }}"--}}
{{--                        alt="Header Avatar">--}}
{{--                </button>--}}
{{--                <div class="dropdown-menu dropdown-menu-right">--}}
{{--                    <!-- item-->--}}
{{--                    <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle font-size-17 align-middle mr-1"></i> @lang('translation.Profile')</a>--}}
{{--                    <div class="dropdown-divider"></div>--}}
{{--                    <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">--}}
{{--                        <i class="bx bx-power-off font-size-17 align-middle mr-1 text-danger"></i>--}}
{{--                        @lang('translation.Logout')--}}
{{--                    </a>--}}
{{--                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
{{--                        @csrf--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
</header>
