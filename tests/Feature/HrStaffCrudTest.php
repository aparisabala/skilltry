<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\AdminUserRole;
use App\Models\AdminUserPermission;
use App\Models\HrLeaveType;
use App\Http\Middleware\SetBootConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class HrStaffCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(SetBootConfig::class);
        if (!defined('V')) {
            define('V', 'test');
        }
    }

    private function admin(string $code = 'SA', array $attributes = []): AdminUser
    {
        $role = AdminUserRole::firstOrCreate(['code' => $code], ['name' => $code]);
        $user = new AdminUser;
        $user->forceFill(array_merge([
            'uuid' => (string) Str::uuid(), 'name' => 'HR Staff Fixture '.Str::random(4),
            'email' => Str::uuid().'@example.test', 'password' => Hash::make('test-password-123'),
            'admin_user_role_id' => $role->id, 'setup_done' => 'yes',
            'admin_type' => 'system_user', 'status' => 'Active',
        ], $attributes))->save();
        return $user;
    }

    private function token(AdminUser $user): string
    {
        return $user->createToken('tests', ['*'], now()->addHour())->plainTextToken;
    }

    /**
     * Generic create -> list -> update -> delete round trip for a scoped
     * (per admin_user_id) Hrm/Staff CRUD feature.
     */
    private function assertScopedCrudRoundTrip(string $base, int $adminUserId, array $storePayload, array $updatePayload, string $model): void
    {
        $storePayload['admin_user_id'] = $adminUserId;

        $this->postJson($base, $storePayload)->assertOk()->assertJsonPath('success', true);
        $row = $model::where('admin_user_id', $adminUserId)->latest('id')->firstOrFail();

        $this->postJson($base.'/list', ['admin_user_id' => $adminUserId])->assertOk();

        $this->putJson($base.'/'.$row->id, array_merge($storePayload, $updatePayload))
            ->assertOk()->assertJsonPath('success', true);
        $row->refresh();
        foreach ($updatePayload as $field => $value) {
            $this->assertEquals($value, $row->{$field});
        }

        $this->postJson($base.'/delete-list', ['ids' => [$row->id]])->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseMissing($model::make()->getTable(), ['id' => $row->id]);
    }

    /**
     * Generic create -> list -> update -> delete round trip for a flat
     * (non-scoped) lookup CRUD feature.
     */
    private function assertFlatCrudRoundTrip(string $base, array $storePayload, array $updatePayload, string $model, string $findBy = 'name'): void
    {
        $this->postJson($base, $storePayload)->assertOk()->assertJsonPath('success', true);
        $row = $model::where($findBy, $storePayload[$findBy])->firstOrFail();

        $this->postJson($base.'/list')->assertOk()->assertJsonFragment([$findBy => $storePayload[$findBy]]);

        $this->putJson($base.'/'.$row->id, array_merge($storePayload, $updatePayload))
            ->assertOk()->assertJsonPath('success', true);
        $row->refresh();
        foreach ($updatePayload as $field => $value) {
            $this->assertEquals($value, $row->{$field});
        }

        $this->postJson($base.'/delete-list', ['ids' => [$row->id]])->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseMissing($model::make()->getTable(), ['id' => $row->id]);
    }

    public function test_education_experience_and_employment_crud_round_trip(): void
    {
        $this->withToken($this->token($this->admin()));
        $employee = $this->admin('ST');

        $this->assertScopedCrudRoundTrip(
            '/api/v1/admin/hrm/staff/education', $employee->id,
            ['degree' => 'BSc', 'institute' => 'Dhaka University'],
            ['degree' => 'MSc'],
            \App\Models\HrEducation::class
        );

        $this->assertScopedCrudRoundTrip(
            '/api/v1/admin/hrm/staff/experience', $employee->id,
            ['organization' => 'Acme Ltd', 'position' => 'Developer', 'from_date' => '2020-01-01'],
            ['position' => 'Senior Developer'],
            \App\Models\HrExperience::class
        );

        $this->assertScopedCrudRoundTrip(
            '/api/v1/admin/hrm/staff/employment', $employee->id,
            ['change_type' => 'Joined', 'effective_date' => '2021-05-01'],
            ['change_type' => 'Confirmed'],
            \App\Models\HrEmployment::class
        );
    }

    public function test_advance_deduction_and_leave_crud_round_trip(): void
    {
        $this->withToken($this->token($this->admin()));
        $employee = $this->admin('ST');
        $leaveType = HrLeaveType::create(['name' => 'Casual Leave', 'is_paid' => 'Yes', 'days_per_year' => 10, 'serial' => 1]);

        $this->assertScopedCrudRoundTrip(
            '/api/v1/admin/hrm/staff/advance', $employee->id,
            ['amount' => 5000, 'advance_date' => '2024-01-01', 'monthly_deduction' => 500],
            ['amount' => 6000, 'status' => 'Pending'],
            \App\Models\HrAdvance::class
        );

        $this->assertScopedCrudRoundTrip(
            '/api/v1/admin/hrm/staff/deduction', $employee->id,
            ['deduction_type' => 'Penalty', 'amount' => 200, 'deduction_month' => '2024-01-01'],
            ['amount' => 300],
            \App\Models\HrDeduction::class
        );

        $this->assertScopedCrudRoundTrip(
            '/api/v1/admin/hrm/staff/leave', $employee->id,
            ['hr_leave_type_id' => $leaveType->id, 'from_date' => '2024-02-01', 'to_date' => '2024-02-03'],
            ['reason' => 'Family event', 'status' => 'Pending'],
            \App\Models\HrLeave::class
        );
    }

    public function test_leave_type_holiday_pay_component_and_pay_grade_flat_crud_round_trip(): void
    {
        $this->withToken($this->token($this->admin()));

        $this->assertFlatCrudRoundTrip(
            '/api/v1/admin/hrm/staff/leave-type',
            ['name' => 'Sick Leave', 'is_paid' => 'Yes', 'days_per_year' => 14],
            ['days_per_year' => 20],
            HrLeaveType::class
        );

        $this->assertFlatCrudRoundTrip(
            '/api/v1/admin/hrm/staff/holiday',
            ['name' => 'Independence Day', 'holiday_date' => '2024-03-26'],
            ['note' => 'National holiday'],
            \App\Models\HrHoliday::class
        );

        $this->assertFlatCrudRoundTrip(
            '/api/v1/admin/hrm/staff/pay-component',
            ['name' => 'House Rent', 'component_type' => 'Earning', 'calc_type' => 'Percent'],
            ['default_value' => 40],
            \App\Models\HrPayComponent::class
        );

        $this->assertFlatCrudRoundTrip(
            '/api/v1/admin/hrm/staff/pay-grade',
            ['name' => 'Grade A', 'min_basic' => 10000, 'max_basic' => 20000],
            ['description' => 'Entry level grade'],
            \App\Models\HrPayGrade::class
        );
    }

    public function test_lib_department_flat_crud_round_trip(): void
    {
        $this->withToken($this->token($this->admin()));

        $this->assertFlatCrudRoundTrip(
            '/api/v1/admin/datalibrary/department',
            ['name' => 'Cardiology'],
            ['name' => 'Cardiology Department'],
            \App\Models\LibDepartment::class
        );
    }

    public function test_per_employee_features_scope_strictly_to_one_admin_user_id(): void
    {
        $this->withToken($this->token($this->admin()));
        $employeeA = $this->admin('ST');
        $employeeB = $this->admin('ST');

        $this->postJson('/api/v1/admin/hrm/staff/education', [
            'admin_user_id' => $employeeA->id, 'degree' => 'BSc', 'institute' => 'University A',
        ])->assertOk()->assertJsonPath('success', true);
        $this->postJson('/api/v1/admin/hrm/staff/education', [
            'admin_user_id' => $employeeB->id, 'degree' => 'BA', 'institute' => 'University B',
        ])->assertOk()->assertJsonPath('success', true);

        $listForA = $this->postJson('/api/v1/admin/hrm/staff/education/list', ['admin_user_id' => $employeeA->id])
            ->assertOk()->json('data');
        $rowsForA = collect($listForA);
        $this->assertTrue($rowsForA->contains(fn ($row) => ($row['institute'] ?? null) === 'University A'));
        $this->assertFalse($rowsForA->contains(fn ($row) => ($row['institute'] ?? null) === 'University B'));

        // Same invariant holds for the scoped index/{admin_user_id} endpoint.
        $indexForA = $this->getJson('/api/v1/admin/hrm/staff/education/'.$employeeA->id)->assertOk();
        $this->assertEquals($employeeA->id, $indexForA->json('data.admin_user_id'));
    }

    public function test_permission_gating_for_education_and_leave_type(): void
    {
        AdminUserPermission::forceCreate(['slug' => 'mobile_api_access', 'user_access' => ['ST']]);
        $staff = $this->admin('ST');
        $this->withToken($this->token($staff));

        $this->getJson('/api/v1/admin/hrm/staff/education/'.$staff->id)->assertForbidden();
        AdminUserPermission::forceCreate(['slug' => 'hr_education_crud_view', 'user_access' => ['ST']]);
        $this->getJson('/api/v1/admin/hrm/staff/education/'.$staff->id)->assertOk();
        $this->postJson('/api/v1/admin/hrm/staff/education', [
            'admin_user_id' => $staff->id, 'degree' => 'BSc', 'institute' => 'X',
        ])->assertForbidden();

        $this->getJson('/api/v1/admin/hrm/staff/leave-type')->assertForbidden();
        AdminUserPermission::forceCreate(['slug' => 'hr_leave_type_crud_view', 'user_access' => ['ST']]);
        $this->getJson('/api/v1/admin/hrm/staff/leave-type')->assertOk();
    }

    public function test_hrm_staff_routes_require_admin_authentication(): void
    {
        $this->getJson('/api/v1/admin/hrm/staff/leave-type')->assertUnauthorized()->assertJsonPath('success', false);
        $this->postJson('/api/v1/admin/hrm/staff/education', ['degree' => 'X'])->assertUnauthorized();
    }
}
