@extends('admin.layouts.main-layout',["tabTitle" => config('i.service_name')." | ".pxLang($data['lang'],'breadCum.title') ])
@section('page')
    <div class="row">
        <div class="col-md-12">
            @can('admin_user_opd_fees_opd_fees_update_view')
                @if($data['doctor'] != null)
                    <div class="">
                        @include('admin.pages.hrm.user.crud.modify.opd-fees.form.update.fragments._breadcum')
                        <div id="pageSideBar" class="pageSideBar">
                        <a href="javascript:void(0)" class="closebtn closeNav">&times;</a>
                        @include('admin.pages.hrm.user.crud.modify.navs.nav')
                    </div>
                        <div class="card rounded page-block">
                            <div class="d-none d-md-block mb-4">
                                    @include('admin.pages.hrm.user.crud.modify.navs.nav')
                                </div>
                                <div class="d-block d-md-none">
                                    <div class="d-flex flex-row justify-content-end align-items-center p-2">
                                        <span class="fs-18 openNav" style="cursor:pointer">&#9776;</span>
                                    </div>
                                </div>
                            <div class="mt-4 p-3">
                                @can('admin_user_opd_fees_opd_fees_update_update')
                                    @if($data['doctor']->opdFees != null)
                                        <form id="frmAdminUserOpdFeesOpdFeesUpdate" autocomplete="off">
                                            <input type="hidden" name="id" value="{{$data['doctor']?->opdFees?->id}}" />
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="card p-2 shadow-card card-border">
                                                                <div class="form-group text-left mb-3">
                                                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.doctor_fees')}}</b> <em class="required">*</em> <span id="doctor_fees_error"></span></label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" name="doctor_fees" id="doctor_fees" value="{{$data['doctor']?->opdFees?->doctor_fees}}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group text-left mb-3">
                                                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.hospital_fees')}}</b> <em class="required">*</em> <span id="hospital_fees_error"></span></label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" name="hospital_fees" id="hospital_fees" value="{{$data['doctor']?->opdFees?->hospital_fees}}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group text-left mb-3">
                                                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.service_fees')}}</b> <em class="required">*</em> <span id="service_fees_error"></span></label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" name="service_fees" id="service_fees" value="{{$data['doctor']?->opdFees?->service_fees}}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group text-left mb-3">
                                                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.ipd_fees')}}</b> <span id="ipd_fees_error"></span></label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" name="ipd_fees" id="ipd_fees" value="{{$data['doctor']?->opdFees?->ipd_fees ?? 0}}">
                                                                    </div>
                                                                    <div class="small text-muted">Charged to the IPD bill when this doctor is called as a consultant. Leave 0 to use the doctor fees above.</div>
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
                                        <p> Invalid fees setup, try again</p>
                                    @endif
                                @else
                                    @include('common.view.fragments.-item-403')
                                @endcan
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card rounded page-block">
                        @include('common.view.fragments.-item-404')
                    </div>
                @endif
            @else
                @include('common.view.fragments.-item-403')
            @endcan
        </div>
    </div>
@endsection
