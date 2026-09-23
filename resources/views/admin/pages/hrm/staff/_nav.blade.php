{{-- Tabs of one employee's HR profile. Needs $data['employee'] (AdminUser). --}}
@php
    $e = $data['employee'];
    $tabs = [
        ['profile', 'Personal Info', 'hr_profile_view', 'bx-user'],
        ['education', 'Education', 'hr_education_crud_view', 'bx-book-open'],
        ['experience', 'Job Experience', 'hr_experience_crud_view', 'bx-briefcase'],
        ['employment', 'Employment History', 'hr_employment_crud_view', 'bx-history'],
        ['salary', 'Salary Setup', 'hr_salary_setup_view', 'bx-money'],
        ['advance', 'Advances', 'hr_advance_crud_view', 'bx-wallet'],
        ['deduction', 'Deductions', 'hr_deduction_crud_view', 'bx-minus-circle'],
        ['leave', 'Leaves', 'hr_leave_crud_view', 'bx-calendar-minus'],
        ['pf', 'Provident Fund', 'hr_pf_view', 'bx-piggy-bank'],
        ['attendance', 'Attendance', 'hr_attendance_view', 'bx-time-five'],
    ];
    $seg = request()->segment(4);
@endphp
<div class="d-flex align-items-center mb-2 px-1">
    <div class="me-3">
        <img src="{{ getRowImage($e) }}" class="rounded-circle" width="44" height="44" alt="">
    </div>
    <div>
        <div class="fw-bold">{{ $e?->name }}</div>
        <div class="small text-muted">{{ $e?->role?->name }} &middot; {{ $e?->email }}</div>
    </div>
    <div class="ms-auto"><a class="btn btn-sm btn-outline-secondary" href="{{ url('admin/hrm/staff') }}"><i class="bx bx-arrow-back"></i> All employees</a></div>
</div>
<ul class="nav nav-pills flex-wrap gap-1 mb-2">
    @foreach($tabs as [$key, $label, $perm, $icon])
        @can($perm)
            <li class="nav-item"><a class="nav-link py-1 px-2 {{ $seg == $key ? 'active' : '' }}" href="{{ url('admin/hrm/staff/'.$key.'/'.$e?->id) }}"><i class="bx {{ $icon }} me-1"></i>{{ $label }}</a></li>
        @endcan
    @endforeach
</ul>
