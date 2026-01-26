<?php

namespace App\Repositories\Admin\System\User\Policy;

interface IAdminUserPolicyRepository {
    public function index($request);
    public function update($request);
}

