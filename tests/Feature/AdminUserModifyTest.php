<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\AdminUserRole;
use App\Models\AdminUserPermission;
use App\Models\AdminUserDesignation;
use App\Models\AdminUserOpdSlot;
use App\Http\Middleware\SetBootConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminUserModifyTest extends TestCase
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
            'uuid' => (string) Str::uuid(), 'name' => 'HR Modify Fixture '.Str::random(4),
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

    public function test_designation_crud_round_trip_scoped_to_employee(): void
    {
        $this->withToken($this->token($this->admin()));
        $employee = $this->admin('ST');
        $base = '/api/v1/admin/hrm/user/crud/modify/designation';

        $this->postJson($base, [
            'admin_user_id' => $employee->id, 'name' => 'Senior Engineer', 'passing_year' => '2018',
        ])->assertOk()->assertJsonPath('success', true);
        $row = AdminUserDesignation::where('admin_user_id', $employee->id)->firstOrFail();

        $this->postJson($base.'/list', ['admin_user_id' => $employee->id])
            ->assertOk()->assertJsonFragment(['name' => 'Senior Engineer']);

        $this->putJson($base.'/'.$row->id, [
            'admin_user_id' => $employee->id, 'name' => 'Principal Engineer',
        ])->assertOk()->assertJsonPath('success', true);
        $this->assertSame('Principal Engineer', $row->fresh()->name);

        $this->postJson($base.'/delete-list', ['ids' => [$row->id]])->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseMissing('admin_user_designations', ['id' => $row->id]);
    }

    public function test_opd_slot_crud_round_trip_scoped_to_doctor(): void
    {
        $this->withToken($this->token($this->admin()));
        $doctor = $this->admin('DC');
        $base = '/api/v1/admin/hrm/user/crud/modify/doctor-opd-slot';

        $this->postJson($base, [
            'admin_user_id' => $doctor->id, 'year' => '2024', 'month' => '5', 'day' => '10', 'slot' => 'Morning',
        ])->assertOk()->assertJsonPath('success', true);
        $row = AdminUserOpdSlot::where('admin_user_id', $doctor->id)->firstOrFail();

        $this->postJson($base.'/list', ['admin_user_id' => $doctor->id])
            ->assertOk()->assertJsonFragment(['slot' => 'Morning']);

        $this->putJson($base.'/'.$row->id, [
            'admin_user_id' => $doctor->id, 'year' => '2024', 'month' => '5', 'day' => '10', 'slot' => 'Evening',
        ])->assertOk()->assertJsonPath('success', true);
        $this->assertSame('Evening', $row->fresh()->slot);

        $this->postJson($base.'/delete-list', ['ids' => [$row->id]])->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseMissing('admin_user_opd_slots', ['id' => $row->id]);
    }

    public function test_opd_fees_single_form_show_and_update_for_a_doctor(): void
    {
        $this->withToken($this->token($this->admin()));
        $doctor = $this->admin('DC');

        $show = $this->getJson('/api/v1/admin/hrm/user/crud/modify/opd-fees/update/'.$doctor->id)
            ->assertOk()->assertJsonPath('success', true);
        $rowId = $show->json('data.doctor.opd_fees.id');
        $this->assertNotEmpty($rowId);

        $this->postJson('/api/v1/admin/hrm/user/crud/modify/opd-fees/update', [
            'id' => $rowId, 'doctor_fees' => 500, 'hospital_fees' => 300, 'service_fees' => 100, 'ipd_fees' => 200,
        ])->assertOk()->assertJsonPath('success', true);

        $this->assertDatabaseHas('admin_user_opd_fees', [
            'id' => $rowId, 'doctor_fees' => 500, 'hospital_fees' => 300, 'service_fees' => 100, 'ipd_fees' => 200,
        ]);
    }

    public function test_opd_fees_endpoint_rejects_a_non_doctor_employee(): void
    {
        $this->withToken($this->token($this->admin()));
        $employee = $this->admin('ST');
        $this->getJson('/api/v1/admin/hrm/user/crud/modify/opd-fees/update/'.$employee->id)
            ->assertStatus(404)->assertJsonPath('success', false);
    }

    public function test_designation_and_opd_slot_scope_strictly_to_one_admin_user_id(): void
    {
        $this->withToken($this->token($this->admin()));
        $employeeA = $this->admin('ST');
        $employeeB = $this->admin('ST');

        $this->postJson('/api/v1/admin/hrm/user/crud/modify/designation', [
            'admin_user_id' => $employeeA->id, 'name' => 'Role A',
        ])->assertOk()->assertJsonPath('success', true);
        $this->postJson('/api/v1/admin/hrm/user/crud/modify/designation', [
            'admin_user_id' => $employeeB->id, 'name' => 'Role B',
        ])->assertOk()->assertJsonPath('success', true);

        $listForA = collect($this->postJson('/api/v1/admin/hrm/user/crud/modify/designation/list', [
            'admin_user_id' => $employeeA->id,
        ])->assertOk()->json('data'));

        $this->assertTrue($listForA->contains(fn ($row) => ($row['name'] ?? null) === 'Role A'));
        $this->assertFalse($listForA->contains(fn ($row) => ($row['name'] ?? null) === 'Role B'));
    }

    public function test_permission_gating_for_designation(): void
    {
        AdminUserPermission::forceCreate(['slug' => 'mobile_api_access', 'user_access' => ['ST']]);
        $staff = $this->admin('ST');
        $this->withToken($this->token($staff));

        $this->postJson('/api/v1/admin/hrm/user/crud/modify/designation/list', ['admin_user_id' => $staff->id])
            ->assertForbidden();

        AdminUserPermission::forceCreate(['slug' => 'admin_user_designation_crud_view', 'user_access' => ['ST']]);
        $this->postJson('/api/v1/admin/hrm/user/crud/modify/designation/list', ['admin_user_id' => $staff->id])
            ->assertOk();
    }

    public function test_modify_routes_require_admin_authentication(): void
    {
        $this->getJson('/api/v1/admin/hrm/user/crud/modify/designation/1')->assertUnauthorized()->assertJsonPath('success', false);
        $this->getJson('/api/v1/admin/hrm/user/crud/modify/opd-fees/update/1')->assertUnauthorized();
    }
}
