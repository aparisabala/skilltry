@extends('admin.layouts.main-layout', ['tabTitle' => config('i.service_name') . ' | Account Report'])
@section('breadCum')
    <h4 class="mb-0">Account Report</h4>
    <div class="page-title-right"><ol class="breadcrumb m-0"><li class="breadcrumb-item">Accounts</li><li class="breadcrumb-item">Reports</li></ol></div>
@endsection
@section('page')
    @php $r = $data['report']; $f = $r['filters']; $apply = $data['meta'][3]; @endphp
    <div class="container-fluid">
        <div class="card"><div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
                <div><a class="small" href="{{ url('admin/account/reports') }}">&larr; All reports</a><h5 class="mb-0">{{ $r['title'] }}</h5><div class="text-muted small">{{ $r['description'] }}</div></div>
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary btn-sm" target="_blank" href="{{ url('admin/account/reports/'.$r['key'].'?'.http_build_query(array_filter(request()->query())).'&print=1') }}"><i class="fa fa-print"></i> Print</a>
                    @if($data['canExport'])<a class="btn btn-outline-success btn-sm" href="{{ url('admin/account/reports/'.$r['key'].'/export?'.http_build_query(array_filter(request()->query()))) }}"><i class="fa fa-download"></i> Export CSV</a>@endif
                </div>
            </div>
            <form method="get" class="row g-2 mb-3">
                @if(in_array('date', $apply))
                    <div class="col-md-2"><label class="form-label small mb-0">From</label><input type="date" name="date_from" value="{{ $f['date_from'] }}" class="form-control form-control-sm"></div>
                    <div class="col-md-2"><label class="form-label small mb-0">To</label><input type="date" name="date_to" value="{{ $f['date_to'] }}" class="form-control form-control-sm"></div>
                @endif
                @if(in_array('to', $apply))
                    <div class="col-md-2"><label class="form-label small mb-0">As at</label><input type="date" name="date_to" value="{{ $f['date_to'] }}" class="form-control form-control-sm"></div>
                @endif
                @if(in_array('ledger', $apply))
                    <div class="col-md-3"><label class="form-label small mb-0">Ledger</label><select name="ledger_id" class="form-control form-control-sm">@foreach($data['ledgers'] as $x)<option value="{{ $x->id }}" @selected($f['ledger_id'] == $x->id)>{{ $x->name }} ({{ $x->ledger_type }})</option>@endforeach</select></div>
                @endif
                @if(in_array('ledger_type', $apply))
                    <div class="col-md-2"><label class="form-label small mb-0">Ledger type</label><select name="ledger_type" class="form-control form-control-sm"><option value="">All</option>@foreach(['cash', 'bank', 'asset', 'income', 'expense'] as $x)<option value="{{ $x }}" @selected($f['ledger_type'] == $x)>{{ ucfirst($x) }}</option>@endforeach</select></div>
                @endif
                <div class="col-auto d-flex align-items-end"><button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-filter"></i> Run report</button></div>
            </form>
            <div class="text-muted small mb-2">{{ $r['count'] }} row(s)</div>
            @include('admin.pages.account.desk._report-table', ['report' => $r])
        </div></div>
    </div>
@endsection
