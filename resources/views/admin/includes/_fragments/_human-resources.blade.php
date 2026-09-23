@canany(['hrm_user_roles_view', 'hrm_user_view', 'hrm_user_policies_view'])
<li class="{{ menuActive('admin/hrm/*') }}">
    <a href="javascript: void(0);" class="has-arrow waves-effect">
        <i class="bx bx-group" aria-hidden="true"></i>
        <span>{{pxLang('admin.main-nav','hrm.menu')}}</span>
    </a>
    <ul class="sub-menu" aria-expanded="true">
        @can('hrm_user_roles_view')
        <li class="{{ menuActive('admin/hrm/user/user-role', 'admin/hrm/user/user-role/*') }}">
            <a href="{{url('admin/hrm/user/user-role')}}">{{pxLang('admin.main-nav','hrm.menu.user_role')}}</a>
        </li>
        @endcan
        @can('hrm_user_view')
        <li class="{{ menuActive('admin/hrm/user/user-list/*') }}">
            <a href="#" class="has-arrow waves-effect">{{pxLang('admin.main-nav','hrm.menu.user')}}</a>
            <ul class="sub-menu" aria-expanded="true">
                @foreach ($data['userRoles'] as $item)
                <li class="{{ menuActive('admin/hrm/user/user-list/'.$item?->id) }}">
                    <a href="{{url('admin/hrm/user/user-list/'.$item?->id)}}">{{$item?->name}}</a>
                </li>
                @endforeach
            </ul>
        </li>
        @endcan
        @can('hrm_user_policies_view')
        <li class="{{ menuActive('admin/hrm/user/user-policy') }}">
            <a href="{{url('admin/hrm/user/user-policy')}}">{{pxLang('admin.main-nav','hrm.menu.user_policy')}}</a>
        </li>
        @endcan
        @canany(['hr_leave_type_crud_view', 'hr_holiday_crud_view', 'hr_pay_component_crud_view', 'hr_pay_grade_crud_view'])
        <li class="{{ menuActive('admin/hrm/staff/leave-type*', 'admin/hrm/staff/holiday*', 'admin/hrm/staff/pay-component*', 'admin/hrm/staff/pay-grade*') }}">
            <a href="#" class="has-arrow waves-effect">Staff Records</a>
            <ul class="sub-menu" aria-expanded="true">
                @can('hr_leave_type_crud_view')
                <li class="{{ menuActive('admin/hrm/staff/leave-type', 'admin/hrm/staff/leave-type/*') }}">
                    <a href="{{url('admin/hrm/staff/leave-type')}}">Leave Types</a>
                </li>
                @endcan
                @can('hr_holiday_crud_view')
                <li class="{{ menuActive('admin/hrm/staff/holiday', 'admin/hrm/staff/holiday/*') }}">
                    <a href="{{url('admin/hrm/staff/holiday')}}">Holidays</a>
                </li>
                @endcan
                @can('hr_pay_component_crud_view')
                <li class="{{ menuActive('admin/hrm/staff/pay-component', 'admin/hrm/staff/pay-component/*') }}">
                    <a href="{{url('admin/hrm/staff/pay-component')}}">Pay Components</a>
                </li>
                @endcan
                @can('hr_pay_grade_crud_view')
                <li class="{{ menuActive('admin/hrm/staff/pay-grade', 'admin/hrm/staff/pay-grade/*') }}">
                    <a href="{{url('admin/hrm/staff/pay-grade')}}">Pay Grades</a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany
    </ul>
</li>
@endcanany
