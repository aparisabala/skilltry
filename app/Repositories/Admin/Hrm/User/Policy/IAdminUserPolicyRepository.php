<?php

namespace App\Repositories\Admin\Hrm\User\Policy;

interface IAdminUserPolicyRepository {
    public function index($request);
    public function update($request);
}

