 <div class="vertical-menu">
    <div class="navbar-brand-box">
        <a href="{{url('admin/dashboard')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{config('i.favicon')}}" alt="" class="admin-logo-sm">
            </span>
            <span class="logo-lg">
                <img src="{{config('i.logo')}}" alt=""  class="admin-logo-lg">
            </span>
        </a>
        <a href="{{url('admin/dashboard')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{config('i.favicon')}}" alt=""  class="admin-logo-sm">
            </span>
            <span class="logo-lg">
                <img src="{{config('i.logo')}}" alt=""  class="admin-logo-lg">
            </span>
        </a>
    </div>
    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>
    <div data-simplebar="" class="sidebar-menu-scroll">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="">
                    <a href="{{url('admin/dashboard')}}" class="">
                        <i class="bx bx-grid-alt"></i>
                        <span>{{pxLang('admin.main-nav','dashboard')}}</span>
                    </a>
                </li>
                @include('admin.includes._fragments._human-resources')
                @include('admin.includes._fragments._data-library')
                @include('admin.includes._fragments._category')
            </ul>
        </div>
    </div>
</div>
