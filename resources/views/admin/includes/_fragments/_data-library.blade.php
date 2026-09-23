@canany(['lib_bank_view', 'lib_board_view', 'lib_degree_view', 'lib_skill_view', 'lib_division_view', 'lib_district_view', 'lib_thana_view'])
<li class="{{ menuActive('admin/datalibrary/*') }}">
    <a href="javascript: void(0);" class="has-arrow waves-effect">
        <i class="bx bx-library" aria-hidden="true"></i>
        <span>{{pxLang('admin.main-nav','datalibrary.menu')}}</span>
    </a>
    <ul class="sub-menu" aria-expanded="true">
        @can('lib_bank_view')
        <li class="{{ menuActive('admin/datalibrary/bank', 'admin/datalibrary/bank/*') }}">
            <a href="{{url('admin/datalibrary/bank')}}">{{pxLang('admin.main-nav','datalibrary.menu.bank')}}</a>
        </li>
        @endcan
        @can('lib_board_view')
        <li class="{{ menuActive('admin/datalibrary/board', 'admin/datalibrary/board/*') }}">
            <a href="{{url('admin/datalibrary/board')}}">{{pxLang('admin.main-nav','datalibrary.menu.board')}}</a>
        </li>
        @endcan
        @can('lib_degree_view')
        <li class="{{ menuActive('admin/datalibrary/degree', 'admin/datalibrary/degree/*') }}">
            <a href="{{url('admin/datalibrary/degree')}}">{{pxLang('admin.main-nav','datalibrary.menu.degree')}}</a>
        </li>
        @endcan
        @can('lib_skill_view')
        <li class="{{ menuActive('admin/datalibrary/skill', 'admin/datalibrary/skill/*') }}">
            <a href="{{url('admin/datalibrary/skill')}}">{{pxLang('admin.main-nav','datalibrary.menu.skill')}}</a>
        </li>
        @endcan
        @can('lib_division_view')
        <li class="{{ menuActive('admin/datalibrary/location/division', 'admin/datalibrary/location/division/*') }}">
            <a href="{{url('admin/datalibrary/location/division')}}">{{pxLang('admin.main-nav','datalibrary.menu.division')}}</a>
        </li>
        @endcan
        @can('lib_district_view')
        <li class="{{ menuActive('admin/datalibrary/location/district', 'admin/datalibrary/location/district/*') }}">
            <a href="{{url('admin/datalibrary/location/district')}}">{{pxLang('admin.main-nav','datalibrary.menu.district')}}</a>
        </li>
        @endcan
        @can('lib_thana_view')
        <li class="{{ menuActive('admin/datalibrary/location/thana', 'admin/datalibrary/location/thana/*') }}">
            <a href="{{url('admin/datalibrary/location/thana')}}">{{pxLang('admin.main-nav','datalibrary.menu.thana')}}</a>
        </li>
        @endcan
    </ul>
</li>
@endcanany
