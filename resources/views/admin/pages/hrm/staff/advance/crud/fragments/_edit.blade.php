<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light"> <a href="{{url('admin/hrm/staff/advance/'.$data['item']?->admin_user_id.'')}}"><span class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span></a> <span class="pt-1"> {{pxLang($data['lang'],'update')}}   </span> </span>
</div>
<div class="mt-4 p-3">
    @can('hr_advance_crud_edit')
        <form id="frmUpdateHrAdvance" autocomplete="off">
            @method('PATCH')
            <input type="hidden" id="patch_id" value="{{$data['item']?->id}}" />
            <input type="hidden" name="admin_user_id" value="{{ $data['item']?->admin_user_id }}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.amount')}}</b> <em class="required">*</em> <span id="amount_error"></span></label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="0.01" class="form-control" name="amount" id="amount" value="{{$data['item']?->amount}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.advance_date')}}</b> <em class="required">*</em> <span id="advance_date_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control dp" name="advance_date" id="advance_date" value="{{$data['item']?->advance_date}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.monthly_deduction')}}</b> <em class="required">*</em> <span id="monthly_deduction_error"></span></label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="0.01" class="form-control" name="monthly_deduction" id="monthly_deduction" value="{{$data['item']?->monthly_deduction}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.reason')}}</b> <em class="required"></em> <span id="reason_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="reason" id="reason" value="{{$data['item']?->reason}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.status')}}</b> <em class="required">*</em> <span id="status_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="status" id="status"><option value="Pending" {{ ($data['item']?->status == 'Pending') ? 'selected' : '' }}>Pending</option><option value="Approved" {{ ($data['item']?->status == 'Approved') ? 'selected' : '' }}>Approved</option><option value="Rejected" {{ ($data['item']?->status == 'Rejected') ? 'selected' : '' }}>Rejected</option><option value="Settled" {{ ($data['item']?->status == 'Settled') ? 'selected' : '' }}>Settled</option></select>
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
