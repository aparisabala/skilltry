<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light"> <a href=""><span class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span></a> <span class="pt-1">{{pxLang($data['lang'],'add')}}  </span> </span>
</div>
<div class="mt-4 p-3">
    @can('hr_deduction_crud_store')
        <form id="frmStoreHrDeduction" autocomplete="off">
            <input type="hidden" name="admin_user_id" value="{{ $data['employee']?->id }}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.deduction_type')}}</b> <em class="required">*</em> <span id="deduction_type_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="deduction_type" id="deduction_type"><option value="Penalty" selected>Penalty</option><option value="Loan / EMI">Loan / EMI</option><option value="Tax">Tax</option><option value="Uniform">Uniform</option><option value="Damage / Loss">Damage / Loss</option><option value="Other">Other</option></select>
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.amount')}}</b> <em class="required">*</em> <span id="amount_error"></span></label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="0.01" class="form-control" name="amount" id="amount" value="">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.deduction_month')}}</b> <em class="required">*</em> <span id="deduction_month_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control dp" name="deduction_month" id="deduction_month" value="">
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
