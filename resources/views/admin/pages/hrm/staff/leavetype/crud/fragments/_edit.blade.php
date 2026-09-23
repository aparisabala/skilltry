<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light"> <a href="{{url('admin/hrm/staff/leave-type')}}"><span class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span></a> <span class="pt-1"> {{pxLang($data['lang'],'update')}}   </span> </span>
</div>
<div class="mt-4 p-3">
    @can('hr_leave_type_crud_edit')
        <form id="frmUpdateHrLeaveType" autocomplete="off">
            @method('PATCH')
            <input type="hidden" id="patch_id" value="{{$data['item']?->id}}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.name')}}</b> <em class="required">*</em> <span id="name_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="name" id="name" value="{{$data['item']?->name}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.is_paid')}}</b> <em class="required">*</em> <span id="is_paid_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="is_paid" id="is_paid"><option value="Yes" {{ ($data['item']?->is_paid == 'Yes') ? 'selected' : '' }}>Yes</option><option value="No" {{ ($data['item']?->is_paid == 'No') ? 'selected' : '' }}>No</option></select>
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.days_per_year')}}</b> <em class="required"></em> <span id="days_per_year_error"></span></label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="1" class="form-control" name="days_per_year" id="days_per_year" value="{{$data['item']?->days_per_year}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.status')}}</b> <em class="required"></em> <span id="status_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="status" id="status"><option value="Active" {{ ($data['item']?->status == 'Active') ? 'selected' : '' }}>Active</option><option value="Inactive" {{ ($data['item']?->status == 'Inactive') ? 'selected' : '' }}>Inactive</option></select>
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
