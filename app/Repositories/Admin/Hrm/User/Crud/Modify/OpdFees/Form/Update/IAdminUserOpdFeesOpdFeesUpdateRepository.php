<?php

namespace App\Repositories\Admin\Hrm\User\Crud\Modify\OpdFees\Form\Update;

use Illuminate\Http\JsonResponse;

interface IAdminUserOpdFeesOpdFeesUpdateRepository {

    public function index($request) : array;
    public function update($request) : JsonResponse;
}
