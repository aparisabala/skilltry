<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light"> <a href=""><span class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span></a> <span class="pt-1">{{pxLang($data['lang'],'add')}}  </span> </span>
</div>
<div class="mt-4 p-3">
    @can('hr_education_crud_store')
        <form id="frmStoreHrEducation" autocomplete="off">
            <input type="hidden" name="admin_user_id" value="{{ $data['employee']?->id }}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.degree')}}</b> <em class="required">*</em> <span id="degree_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="degree" id="degree" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.institute')}}</b> <em class="required">*</em> <span id="institute_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="institute" id="institute" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.board_university')}}</b> <em class="required"></em> <span id="board_university_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="board_university" id="board_university" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.subject')}}</b> <em class="required"></em> <span id="subject_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="subject" id="subject" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.passing_year')}}</b> <em class="required"></em> <span id="passing_year_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="passing_year" id="passing_year" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.result')}}</b> <em class="required"></em> <span id="result_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="result" id="result" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.description')}}</b> <em class="required"></em> <span id="description_error"></span></label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
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
