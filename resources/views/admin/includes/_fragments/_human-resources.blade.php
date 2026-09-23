@canany(['hrm_user_roles_view', 'hrm_user_view', 'hrm_user_policies_view', 'hr_employee_view', 'hr_attendance_view', 'hr_roster_view', 'hr_payroll_view', 'hr_report_view', 'hr_settings_view', 'hr_paycomponent_crud_view', 'hr_paygrade_crud_view', 'hr_leavetype_crud_view', 'hr_holiday_crud_view'])
<li class="{{ menuActive('admin/hrm/*') }}">
    <a href="javascript: void(0);" class="has-arrow waves-effect">
        <i class="bx bx-group" aria-hidden="true"></i>
        <span>{{pxLang('admin.main-nav','hrm.menu')}}</span>
    </a>
    <ul class="sub-menu" aria-expanded="true">
        @can('hr_employee_view')
        <li class="{{ menuActive('admin/hrm/staff', 'admin/hrm/staff/*') }}">
            <a href="{{url('admin/hrm/staff')}}">Employees</a>
        </li>
        @endcan
        @can('hr_attendance_view')
        <li class="{{ menuActive('admin/hrm/attendance', 'admin/hrm/attendance/*') }}">
            <a href="{{url('admin/hrm/attendance')}}">Attendance</a>
        </li>
        @endcan
        @can('hr_roster_view')
        <li class="{{ menuActive('admin/hrm/roster', 'admin/hrm/roster/*') }}">
            <a href="{{url('admin/hrm/roster')}}">Duty Roster</a>
        </li>
        @endcan
        @can('hr_payroll_view')
        <li class="{{ menuActive('admin/hrm/payroll', 'admin/hrm/payroll/*') }}">
            <a href="{{url('admin/hrm/payroll')}}">Payroll</a>
        </li>
        @endcan
        @can('hr_report_view')
        <li class="{{ menuActive('admin/hrm/report', 'admin/hrm/report/*') }}">
            <a href="{{url('admin/hrm/report')}}">HR Reports</a>
        </li>
        @endcan
        @canany(['hr_paycomponent_crud_view', 'hr_paygrade_crud_view', 'hr_leavetype_crud_view', 'hr_holiday_crud_view', 'hr_settings_view'])
        <li class="{{ menuActive('admin/hrm/setup/paycomponent', 'admin/hrm/setup/paygrade', 'admin/hrm/setup/leavetype', 'admin/hrm/setup/holiday', 'admin/hrm/settings') }}">
            <a href="#" class="has-arrow waves-effect">HR Setup</a>
            <ul class="sub-menu" aria-expanded="true">
                @can('hr_paycomponent_crud_view')<li><a href="{{url('admin/hrm/setup/paycomponent')}}">Pay Components</a></li>@endcan
                @can('hr_paygrade_crud_view')<li><a href="{{url('admin/hrm/setup/paygrade')}}">Pay Grades / Scale</a></li>@endcan
                @can('hr_leavetype_crud_view')<li><a href="{{url('admin/hrm/setup/leavetype')}}">Leave Types</a></li>@endcan
                @can('hr_holiday_crud_view')<li><a href="{{url('admin/hrm/setup/holiday')}}">Holidays</a></li>@endcan
                @can('hr_settings_view')<li><a href="{{url('admin/hrm/settings')}}">HR Rules</a></li>@endcan
            </ul>
        </li>
        @endcanany
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
    </ul>
</li>
@endcanany
