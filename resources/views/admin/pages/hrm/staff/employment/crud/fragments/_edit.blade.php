<div class="bg-info pl-2 page-fragment-bar">
    <span class="text-light"> <a href="{{url('admin/hrm/staff/employment/'.$data['item']?->admin_user_id.'')}}"><span class="badge badge-info cursor-pointer"> <i class='fa-solid fa-arrow-left fs-16'></i></span></a> <span class="pt-1"> {{pxLang($data['lang'],'update')}}   </span> </span>
</div>
<div class="mt-4 p-3">
    @can('hr_employment_crud_edit')
        <form id="frmUpdateHrEmployment" autocomplete="off">
            @method('PATCH')
            <input type="hidden" id="patch_id" value="{{$data['item']?->id}}" />
            <input type="hidden" name="admin_user_id" value="{{ $data['item']?->admin_user_id }}" />
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-2 shadow-card card-border">
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.change_type')}}</b> <em class="required">*</em> <span id="change_type_error"></span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="change_type" id="change_type">
                                            @foreach(['Joined','Confirmed','Promotion','Transfer','Increment','Demotion','Suspended','Resigned','Terminated','Retired'] as $ct)
                                                <option value="{{ $ct }}" {{ $data['item']?->change_type == $ct ? 'selected' : '' }}>{{ $ct }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.effective_date')}}</b> <em class="required">*</em> <span id="effective_date_error"></span></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="effective_date" id="effective_date" value="{{$data['item']?->effective_date}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.lib_department_id')}}</b> <em class="required"></em> <span id="lib_department_id_error"></span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="lib_department_id" id="lib_department_id" value="{{$data['item']?->lib_department_id}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.designation_title')}}</b> <em class="required"></em> <span id="designation_title_error"></span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="designation_title" id="designation_title" value="{{$data['item']?->designation_title}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.salary')}}</b> <em class="required"></em> <span id="salary_error"></span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" class="form-control" name="salary" id="salary" value="{{$data['item']?->salary}}">
                                    </div>
                                </div>
                                <div class="form-group text-left mb-3">
                                    <label class="form-label"> <b>{{pxLang($data['lang'],'fields.note')}}</b> <em class="required"></em> <span id="note_error"></span></label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="note" id="note" rows="3">{{$data['item']?->note}}</textarea>
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
