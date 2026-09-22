<form id="frmUpdateAdminUserPermission" autocomplete="off">
    @can('hrm_user_policies_edit')
    <div class="mb-3 mt-3 text-end">
        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-save"></i> {{ pxLang($data['lang'],'','common.btns.crud_update') }}</button>
    </div>
    @endcan
    @foreach ($data['systePolicies'] as $systemPolicy)
        @foreach ($systemPolicy['policies'] as $moduelPolicy)
            @foreach ($moduelPolicy['policies'] as $actionPolicy)
                @if($moduelPolicy['name'] == request()?->policy_name)
                <h4 class=""> {{$moduelPolicy['name']}} - {{$actionPolicy['name']}} </h4>
                <div class="table-responsive">
                    <table class="table table-striped dataTable">
                        <thead>
                            <tr>
                                <th style="width: 100px;"> Action </th>
                                <th style="width: 200px;"> Key </th>
                                @foreach ($data['availableUser'] as $key =>  $item)
                                    <th> {{$item?->name}} </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($actionPolicy['keys'] as $action)
                                @php
                                    $uniqueKey = getPolicyKey(\Str::class,$actionPolicy['name'].'_'.$action);
                                    $row = $data['permissions']?->where('slug','=',$uniqueKey)->first();
                                @endphp
                                <tr>
                                    <td>
                                        <input type="hidden" name="slug[]" value="{{$row?->id}}" />
                                        {{$action}}
                                    </td>
                                    <td>
                                        {{$uniqueKey}}
                                    </td>
                                    @foreach ($data['availableUser'] as $key =>  $item)
                                        <td> <input {{in_array($item?->code,$row?->user_access ?? []) ? 'checked' : ''}} name="user_access[{{$row?->id}}][]" value="{{$item?->code}}" type="checkbox" /> </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            @endforeach
        @endforeach
    @endforeach
    @can('hrm_user_policies_edit')
        <div class="mb-3 mt-3 text-end">
            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-save"></i> {{ pxLang($data['lang'],'','common.btns.crud_update') }}</button>
        </div>
    @endcan

</form>
