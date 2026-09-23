@extends('admin.layouts.main-layout',["tabTitle" => config('i.service_name')." | ".pxLang($data['lang'],'breadCum.title') ])
@section('page')
    <div class="row">
        <div class="col-md-12">
            @can('admin_user_designation_crud_view')
                @if($data['item'] != null || $data['type'] != "edit" && $data['doctor'] != null)
                    <div class="">
                        <div id="pageSideBar" class="pageSideBar">
                            <a href="javascript:void(0)" class="closebtn closeNav">&times;</a>
                            @include('admin.pages.hrm.user.crud.modify.navs.nav')
                        </div>
                        <div class="page-block-body">
                            @if($data['type'] == "add")
                                @include('admin.pages.hrm.user.crud.modify.designation.crud.fragments._breadcum')
                                @include('admin.pages.hrm.user.crud.modify.designation.crud.fragments._actions')
                                <div class="card rounded page-block">
                                    <div class="d-none d-md-block mb-4">
                                    @include('admin.pages.hrm.user.crud.modify.navs.nav')
                                </div>
                                <div class="d-block d-md-none">
                                    <div class="d-flex flex-row justify-content-end align-items-center p-2">
                                        <span class="fs-18 openNav" style="cursor:pointer">&#9776;</span>
                                    </div>
                                </div>
                                    <div id="defaultPage" class="table-list pages">
                                        <div class="mt-2 p-2 p-md-4">
                                            <input type="hidden" id="page-lang" value="{{ json_encode(Lang::get(config('pxcommands.language')[$data['lang']])) }}" />
                                            <input type="hidden" id="admin_user_id" value="{{$data['admin_user_id']}}" />
                                            @if(count($data['items']) > 0)
                                                @include('common.view.fragments._show-selected')
                                                @include('common.view.fragments._pdf-layout',['docTitle' => 'AdminUserDesignation List'])
                                                <div class="table-responsive">
                                                    <table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" id="dtAdminUserDesignation"></table>
                                                </div>
                                            @else
                                                @include('common.view.fragments._no-list-data')
                                            @endif
                                        </div>
                                    </div>
                                    <div id="addPage" class="d-none pages">
                                        @include('admin.pages.hrm.user.crud.modify.designation.crud.fragments._add')
                                    </div>
                                </div>
                            @else
                                <div id="editPage" class="pages">
                                    <div class="card rounded-0 pb-3">
                                        <div id="loadEdit" class="w-100">
                                            @include('admin.pages.hrm.user.crud.modify.designation.crud.fragments._edit')
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card rounded page-block">
                        @include('common.view.fragments.-item-404')
                    </div>
                @endif
            @else
                <div class="card rounded page-block">
                    @include('common.view.fragments.-item-403')
                </div>
            @endcan
        </div>
    </div>
@endsection
