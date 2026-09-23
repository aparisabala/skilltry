@section('breadCum')
    <h4 class="mb-0">
        <a href="{{ url('admin/account/transaction/' . $data['draft']?->tran_type . '/' . $data['draft']?->tran_method) }}">
            <span class="badge bg-info"><i class="fa fa-arrow-alt-circle-left"></i>
            </span>
        </a>
        {{pxLang($data['lang'], 'breadCum.title')}}
        &raquo; {{pxLang($data['lang'], 'breadCum.b1')}}
        &raquo; {{ $data['draft']?->tran_date }}
    </h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">{{pxLang($data['lang'], 'breadCum.b1')}}</a></li>
            <li class="breadcrumb-item"> {{pxLang($data['lang'], 'breadCum.b2')}} </li>
        </ol>
    </div>
@endsection