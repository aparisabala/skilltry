<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light"> <a href="{{url('admin/category/'.$data['category']->id.'/subcategory/'.$data['subcategory']->id.'/specialization')}}"><span class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span></a> <span class="pt-1"> {{pxLang($data['lang'],'update')}}   </span> </span>
</div>
<div class="mt-4 p-3">
    @can('lib_specialization_edit')
        <form id="frmUpdateLibSpecialization" autocomplete="off" enctype="multipart/form-data">
            <input type="hidden" id="patch_id" value="{{$data['item']?->id}}" />
            <input type="hidden" id="current-category-id" value="{{$data['category']->id}}" />
            <input type="hidden" id="current-subcategory-id" value="{{$data['subcategory']->id}}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.name')}}</b> <em class="required">*</em> <span id="name_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="name" id="name" value="{{$data['item']?->name}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.image')}}</b> <span id="image_error"></span></label>
                                    @if($data['item']?->image)
                                        <div class="mb-2"><img src="{{getRowImage($data['item'], '300X300')}}" class="img-fluid" style="max-height:80px" /></div>
                                    @endif
                                    <div class="input-group">
                                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                    </div>
                                </div>
                                <div class="mb-3 mt-3 text-end">
                                    <button class="btn btn-info btn-sm" type="submit"><i class="fa fa-save"></i> {{pxLang($data['lang'],'','common.btns.crud_action_update')}} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        @include('common.view.fragments.-item-403')
    @endcan
</div>
