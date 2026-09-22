<?php

namespace App\Services;

use App\Models\AdminUser;
use Illuminate\Support\Facades\Gate;

/** HMS API permission check, using the explicit actor without changing session guards. */
class ApiAccessService
{
    public const ACCESS = 'mobile_api_access';

    public function canUseApi(AdminUser $user): bool
    {
        return $this->allows($user, self::ACCESS);
    }

    public function allows(AdminUser $user, string $slug): bool
    {
        return Gate::has($slug)
            ? Gate::forUser($user)->allows($slug)
            : $user->hasPermission([]);
    }
}
