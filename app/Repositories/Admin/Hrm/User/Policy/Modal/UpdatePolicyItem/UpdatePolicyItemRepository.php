<?php

namespace App\Repositories\Admin\Hrm\User\Policy\Modal\UpdatePolicyItem;

use App\Models\AdminUserRole;
use App\Repositories\Admin\Hrm\User\Policy\AdminUserPolicyRepository;
use App\Repositories\BaseRepository;

class UpdatePolicyItemRepository extends BaseRepository implements IUpdatePolicyItemRepository
{

    public function __construct(private AdminUserPolicyRepository $adminUserPolicyRepo)
    {

    }
    /**
     * Modal  data
     *
     * @param Request $request
     * @return array
     */
    public function display($request) : array
    {
        $data =  $this->adminUserPolicyRepo->setPolicy();
        return $data;
    }
}
