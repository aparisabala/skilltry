@extends('admin.layouts.main-layout',["tabTitle" => config('i.service_name')." | ".pxLang($data['lang'],'breadCum.title') ])
@section('page')
    <div class="row">
        <div class="col-md-12">
           <div class="card rounded page-block">
                @can('hrm_user_policies_view')
                    @section('breadCum')
                        <h4 class="mb-0">{{pxLang($data['lang'],'breadCum.title')}}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{pxLang($data['lang'],'breadCum.b1')}}</a></li>
                                <li class="breadcrumb-item"> {{pxLang($data['lang'],'breadCum.b2')}} </li>
                            </ol>
                        </div>
                    @endsection
                    @php
                        $trigModifyPolicy = [
                            'body' => [],
                            'modalCallback' => 'updatePolicyItem',
                            'element'=>'updatePolicyItem',
                            'script'=>'admin/hrm/user/policy/update-policy-item/display',
                            'title'=>'Update',
                            'modalSize' => 'xl',
                            'globLoader'=>false,
                        ];
                    @endphp
                    <div id="defaultPage" class="table-list pages">
                        <div class="mt-2 p-2 p-md-4">
                            @foreach ($data['systePolicies'] as $systemPolicy)
                                <h2 class="text-center"> {{$systemPolicy['name']}} </h2>
                                <div class="mt-3">
                                    <table class="table table-striped dataTable">
                                        <thead>
                                            <tr>
                                                <th>Module Name</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($systemPolicy['policies'] as $moduelPolicy)
                                            @php
                                                $trigModifyPolicy = [
                                                    ...$trigModifyPolicy,
                                                    'body' => ['policy_name' => $moduelPolicy['name']],
                                                    'title' => ' Update '.$moduelPolicy['name']
                                                ]
                                            @endphp
                                            <tr>
                                                <td>{{$moduelPolicy['name']}}</td>
                                                <td class="text-end">
                                                    <span data-bs-toggle='modal' data-bs-target='.editmodal' data-edit-prop='{{json_encode($trigModifyPolicy)}}' class="badge bg-primary cursor-pointer"> Modify </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    @include('common.view.fragments.-item-403')
                @endcan
            </div>
        </div>
    </div>
@endsection
