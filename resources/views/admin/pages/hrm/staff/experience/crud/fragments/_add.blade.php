<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light"> <a href=""><span class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span></a> <span class="pt-1">{{pxLang($data['lang'],'add')}}  </span> </span>
</div>
<div class="mt-4 p-3">
    @can('hr_experience_crud_store')
        <form id="frmStoreHrExperience" autocomplete="off">
            <input type="hidden" name="admin_user_id" value="{{ $data['employee']?->id }}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.organization')}}</b> <em class="required">*</em> <span id="organization_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="organization" id="organization" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.position')}}</b> <em class="required">*</em> <span id="position_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="position" id="position" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.from_date')}}</b> <em class="required">*</em> <span id="from_date_error"></span></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="from_date" id="from_date" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.to_date')}}</b> <em class="required"></em> <span id="to_date_error"></span></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="to_date" id="to_date" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.last_salary')}}</b> <em class="required"></em> <span id="last_salary_error"></span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" class="form-control" name="last_salary" id="last_salary" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.leaving_reason')}}</b> <em class="required"></em> <span id="leaving_reason_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="leaving_reason" id="leaving_reason" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.responsibilities')}}</b> <em class="required"></em> <span id="responsibilities_error"></span></label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="responsibilities" id="responsibilities" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="mb-3 mt-3 text-end">
                                    <button class="btn btn-info btn-sm" type="submit"><i class="fa fa-plus"></i> {{pxLang($data['lang'],'','common.btns.crud_action_add')}} </button>
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
