<?php

namespace App\Repositories\Api\V1\Admin\Auth;

use App\Models\AdminUser;
use App\Repositories\BaseRepository;
use App\Services\PxCommandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminApiAuthRepository extends BaseRepository implements IAdminApiAuthRepository
{
    /** Token lifetime in days */
    private const TOKEN_DAYS = 30;

    public function login(Request $request): JsonResponse
    {
        $login = trim((string) $request->email);
        $column = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_number';
        $user = AdminUser::where($column, $login)->first();

        if (empty($user) || !Hash::check((string) $request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Incorrect credentials'], 401);
        }
        if ($user->status == 'Disabled') {
            return response()->json(['success' => false, 'message' => 'Account disabled'], 403);
        }

        if (!app(\App\Services\ApiAccessService::class)->canUseApi($user)) {
            return response()->json(['success' => false, 'message' => 'Mobile API access is not enabled for your account'], 403);
        }

        $expiresAt = now()->addDays(self::TOKEN_DAYS);
        $token = $user->createToken($request->device_name ?: 'mobile', ['*'], $expiresAt);

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token->plainTextToken,
                'token_type' => 'Bearer',
                'expires_at' => $expiresAt->toIso8601String(),
                'setup_required' => $user->setup_done == 'no',
                'user' => $this->userPayload($user),
            ],
        ]);
    }

    public function me(AdminUser $user): JsonResponse
    {
        Auth::guard('admin')->setUser($user);
        Auth::shouldUse('admin');

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $this->userPayload($user),
                'setup_required' => $user->setup_done == 'no',
                'permissions' => app(PxCommandService::class)->getPolicies(),
            ],
        ]);
    }

    public function logout(AdminUser $user): JsonResponse
    {
        $user->currentAccessToken()?->delete();
        return response()->json(['success' => true, 'message' => 'Logged out']);
    }

    private function userPayload(AdminUser $user): array
    {
        return [
            'id' => $user->id,
            'uuid' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'mobile_number' => $user->mobile_number,
            'role' => ['code' => $user->role?->code, 'name' => $user->role?->name],
        ];
    }
}
