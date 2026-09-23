<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light"> <a href="{{url('admin/hrm/user/crud/modify/doctor-opd-slot/'.$data['item']?->admin_user_id)}}"><span class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span></a> <span class="pt-1"> {{pxLang($data['lang'],'update')}}   </span> </span>
</div>
<div class="mt-4 p-3">
    @can('admin_user_opd_slot_crud_edit')
        <form id="frmUpdateAdminUserOpdSlot" autocomplete="off">
            @method('PATCH')
            <input type="hidden" id="patch_id" value="{{$data['item']?->id}}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.year')}}</b> <em class="required">*</em> <span id="year_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="year" id="year">
                                            <option> -- Select Year -- </option>
                                            @foreach (getYearList(now()->format('Y'),now()->format('Y')+1) as $item)
                                                <option  {{ ($data['item']?->year == $item) ? 'selected':'' }} value="{{ $item }}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.month')}}</b> <em class="required">*</em> <span id="month_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="month" id="month">
                                            <option> -- Select Month -- </option>
                                            @foreach (getMonthList() as $item)
                                                <option {{ ($data['item']?->month == $item) ? 'selected':'' }} value="{{ $item }}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.day')}}</b> <em class="required">*</em> <span id="day_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="day" id="day">
                                            <option> -- Select Day -- </option>
                                            @foreach (getDayList() as $item)
                                                <option {{ ($data['item']?->day == $item) ? 'selected':'' }} value="{{ $item }}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.slot')}}</b> <em class="required">*</em> <span id="slot_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="slot" id="slot" value="{{ $data['item']?->slot }}" >
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
