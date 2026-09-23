<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light"> <a href=""><span class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span></a> <span class="pt-1">{{pxLang($data['lang'],'add')}}  </span> </span>
</div>
<div class="mt-4 p-3">
    @can('hr_leave_crud_store')
        <form id="frmStoreHrLeave" autocomplete="off">
            <input type="hidden" name="admin_user_id" value="{{ $data['employee']?->id }}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.hr_leave_type_id')}}</b> <em class="required">*</em> <span id="hr_leave_type_id_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="hr_leave_type_id" id="hr_leave_type_id"><option value="">-- {{pxLang('','','common.text.option_select')}} --</option>@foreach($data['leaveTypes'] as $opt)<option value="{{$opt?->id}}" >{{$opt?->name}}</option>@endforeach</select>
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.from_date')}}</b> <em class="required">*</em> <span id="from_date_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control dp" name="from_date" id="from_date" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.to_date')}}</b> <em class="required">*</em> <span id="to_date_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control dp" name="to_date" id="to_date" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.reason')}}</b> <em class="required"></em> <span id="reason_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="reason" id="reason" value="">
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
