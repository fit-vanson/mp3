<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex" >
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{route('home.index')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <hr>
                        <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="30">
                    </span>
                </a>
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
                        @if(isset($button))
                            @foreach($button as $key=>$item)
                                <button type="button" id="{{$item['id']}}" class="btn btn-{{$item['style']}} waves-effect waves-light">{{$key}}</button>
                            @endforeach
                        @endif
                </div>
            </div>
        </div>
    </div>
</header>
