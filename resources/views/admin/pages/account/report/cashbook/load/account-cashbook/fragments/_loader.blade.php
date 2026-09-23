<div class="mt-4 p-3">
    @can('account_cashbook_load_view_load')
        <form id="frmLoadAccountCashbook" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="card p-2 shadow-card card-border">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label class="form-label"> <b>{{pxLang($data['lang'],'fields.start_date')}}</b> <em class="required">*</em> <span id="start_date_error"></span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="start_date" id="start_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label class="form-label"> <b>{{pxLang($data['lang'],'fields.end_date')}}</b> <em class="required">*</em> <span id="end_date_error"></span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="end_date" id="end_date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 mt-3 text-end">
                                    <button class="btn btn-info btn-sm" type="submit"><i class="fa fa-arrow-down"></i> {{pxLang($data['lang'],'','common.btns.crud_load')}} </button>
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

