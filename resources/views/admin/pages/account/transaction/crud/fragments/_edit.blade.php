<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light">
        <a href="{{url('admin/account/transaction/' . $data['item']?->tran_type . '/' . $data['item']?->tran_method)}}">
            <span class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span>
        </a>
        <span class="pt-1"> {{pxLang($data['lang'], 'update')}} </span>
    </span>
</div>
<div class="mt-4 p-3">
    @can('ac_draft_balance_sheet_crud_edit')
        <form id="frmUpdateAcDraftBalanceSheet" autocomplete="off">
            @method('PATCH')
            <input type="hidden" id="patch_id" value="{{$data['item']?->id}}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'], 'fields.tran_date')}}</b> <em class="required">*</em> <span id="tran_date_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control tran_date" name="tran_date" id="tran_date" value="{{ $data['item']?->tran_date }}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'], 'fields.debit_to')}}</b> <em class="required">*</em> <span id="debit_to_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="debit_to" id="debit_to">
                                            <option value="">--{{pxLang($data['lang'], '', 'common.text.option_select')}}--</option>
                                            @foreach ($data['debit_ledgers'] as $item)
                                                <option {{ ($data['item']?->debit_to == $item?->id) ? 'selected' : '' }} value="{{ $item?->id }}">{{ $item?->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'], 'fields.credit_to')}}</b> <em class="required">*</em> <span id="credit_to_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="credit_to" id="credit_to">
                                            <option value="">--{{pxLang($data['lang'], '', 'common.text.option_select')}}--</option>
                                            @foreach ($data['credit_ledgers'] as $item)
                                                <option {{ ($data['item']?->credit_to == $item?->id) ? 'selected' : '' }} value="{{ $item?->id }}">{{ $item?->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 mt-3 text-end">
                                    <button class="btn btn-info btn-sm" type="submit"><i class="fa fa-save"></i>
                                        {{pxLang($data['lang'], '', 'common.btns.crud_action_update')}} </button>
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