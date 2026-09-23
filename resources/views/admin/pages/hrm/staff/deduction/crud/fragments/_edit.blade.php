<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light"> <a href="{{url('admin/hrm/staff/deduction/'.$data['item']?->admin_user_id.'')}}"><span class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span></a> <span class="pt-1"> {{pxLang($data['lang'],'update')}}   </span> </span>
</div>
<div class="mt-4 p-3">
    @can('hr_deduction_crud_edit')
        <form id="frmUpdateHrDeduction" autocomplete="off">
            @method('PATCH')
            <input type="hidden" id="patch_id" value="{{$data['item']?->id}}" />
            <input type="hidden" name="admin_user_id" value="{{ $data['item']?->admin_user_id }}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.deduction_type')}}</b> <em class="required">*</em> <span id="deduction_type_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="deduction_type" id="deduction_type"><option value="Penalty" {{ ($data['item']?->deduction_type == 'Penalty') ? 'selected' : '' }}>Penalty</option><option value="Loan / EMI" {{ ($data['item']?->deduction_type == 'Loan / EMI') ? 'selected' : '' }}>Loan / EMI</option><option value="Tax" {{ ($data['item']?->deduction_type == 'Tax') ? 'selected' : '' }}>Tax</option><option value="Uniform" {{ ($data['item']?->deduction_type == 'Uniform') ? 'selected' : '' }}>Uniform</option><option value="Damage / Loss" {{ ($data['item']?->deduction_type == 'Damage / Loss') ? 'selected' : '' }}>Damage / Loss</option><option value="Other" {{ ($data['item']?->deduction_type == 'Other') ? 'selected' : '' }}>Other</option></select>
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.amount')}}</b> <em class="required">*</em> <span id="amount_error"></span></label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="0.01" class="form-control" name="amount" id="amount" value="{{$data['item']?->amount}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.deduction_month')}}</b> <em class="required">*</em> <span id="deduction_month_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control dp" name="deduction_month" id="deduction_month" value="{{$data['item']?->deduction_month}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.reason')}}</b> <em class="required"></em> <span id="reason_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="reason" id="reason" value="{{$data['item']?->reason}}">
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
