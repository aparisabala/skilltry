@extends('admin.layouts.main-layout',["tabTitle" => config('i.service_name')." | ".pxLang($data['lang'],'breadCum.title') ])
@section('page')
    <div class="row">
        <div class="col-md-12">
            @can('lib_specialization_view')
                @if($data['category'] == null || $data['subcategory'] == null)
                    <div class="card rounded page-block">
                        @include('common.view.fragments.-item-404')
                    </div>
                @elseif($data['item'] != null || $data['type'] != "edit")
                    <div class="">
                        @include('admin.pages.category.specialization.crud.fragments._breadcum')
                        <div class="page-block-body">
                            @if($data['type'] == "add")
                                @include('admin.pages.category.specialization.crud.fragments._actions')
                                <div class="card rounded page-block">
                                    <div id="defaultPage" class="table-list pages">
                                        <div class="mt-2 p-2 p-md-4">
                                            <input type="hidden" id="page-lang" value="{{ json_encode(Lang::get(config('pxcommands.language')[$data['lang']])) }}" />
                                            <input type="hidden" id="current-category-id" value="{{$data['category']->id}}" />
                                            <input type="hidden" id="current-subcategory-id" value="{{$data['subcategory']->id}}" />
                                            @if(count($data['items']) > 0)
                                                @include('common.view.fragments._show-selected')
                                                @include('common.view.fragments._pdf-layout',['docTitle' => 'Specialization List'])
                                                <div class="table-responsive">
                                                    <table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" id="dtLibSpecialization"></table>
                                                </div>
                                            @else
                                                @include('common.view.fragments._no-list-data')
                                            @endif
                                        </div>
                                    </div>
                                    <div id="addPage" class="d-none pages">
                                        @include('admin.pages.category.specialization.crud.fragments._add')
                                    </div>
                                </div>
                            @else
                                <div id="editPage" class="pages">
                                    <div class="card rounded-0 pb-3">
                                        <div id="loadEdit" class="w-100">
                                            @include('admin.pages.category.specialization.crud.fragments._edit')
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
