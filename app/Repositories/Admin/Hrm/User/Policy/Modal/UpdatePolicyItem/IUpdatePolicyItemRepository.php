<?php

namespace App\Repositories\Admin\Hrm\User\Policy\Modal\UpdatePolicyItem;

use Illuminate\Http\JsonResponse;

interface IUpdatePolicyItemRepository {

    public function display($request) : array;
}
