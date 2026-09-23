<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light"> <a href=""><span class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span></a> <span class="pt-1">{{pxLang($data['lang'],'add')}}  </span> </span>
</div>
<div class="mt-4 p-3">
    @can('hr_employment_crud_store')
        <form id="frmStoreHrEmployment" autocomplete="off">
            <input type="hidden" name="admin_user_id" value="{{ $data['employee']?->id }}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.change_type')}}</b> <em class="required">*</em> <span id="change_type_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="change_type" id="change_type">
                                            <option value="Joined" selected>Joined</option>
                                            <option value="Confirmed">Confirmed</option>
                                            <option value="Promotion">Promotion</option>
                                            <option value="Transfer">Transfer</option>
                                            <option value="Increment">Increment</option>
                                            <option value="Demotion">Demotion</option>
                                            <option value="Suspended">Suspended</option>
                                            <option value="Resigned">Resigned</option>
                                            <option value="Terminated">Terminated</option>
                                            <option value="Retired">Retired</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.effective_date')}}</b> <em class="required">*</em> <span id="effective_date_error"></span></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="effective_date" id="effective_date" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.lib_department_id')}}</b> <em class="required"></em> <span id="lib_department_id_error"></span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="lib_department_id" id="lib_department_id" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.designation_title')}}</b> <em class="required"></em> <span id="designation_title_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="designation_title" id="designation_title" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.salary')}}</b> <em class="required"></em> <span id="salary_error"></span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" class="form-control" name="salary" id="salary" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.note')}}</b> <em class="required"></em> <span id="note_error"></span></label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="note" id="note" rows="3"></textarea>
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
