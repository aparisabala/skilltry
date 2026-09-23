<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light"> <a
            href="{{url('admin/account/transaction/crud/manage-item/' . $data['item']?->draft?->id)}}"><span
                class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span></a> <span
            class="pt-1"> {{pxLang($data['lang'], 'update')}} </span> </span>
</div>
<div class="mt-4 p-3">
    @can('ac_draft_balance_sheet_item_crud_edit')
        <form id="frmUpdateAcDraftBalanceSheetItem" autocomplete="off">
            @method('PATCH')
            <input type="hidden" id="patch_id" value="{{$data['item']?->id}}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'], 'fields.folio_number')}}</b> <em
                                            class="required">*</em> <span id="folio_number_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="folio_number" id="folio_number"
                                            value="{{ $data['item']?->folio_number }}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'], 'fields.description')}}</b> <em
                                            class="required">*</em> <span id="description_error"></span></label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="description" id="description"
                                            rows="4">{{ $data['item']?->description }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'], 'fields.amount')}}</b> <em
                                            class="required">*</em> <span id="amount_error"></span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="amount" id="amount"
                                            value="{{ $data['item']?->amount }}">
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