@extends('admin.layouts.main-layout', ['tabTitle' => config('i.service_name') . ' | Account Reports'])
@section('page')
    <div class="container-fluid">
                <h4 class="mb-3">Account Reports</h4>
        @foreach($data['catalogue'] as $group => $reports)
            <h6 class="text-uppercase text-muted mt-3 mb-2">{{ $group }}</h6>
            <div class="row g-3">
                @foreach($reports as $r)
                    <div class="col-md-6 col-xl-4">
                        <a href="{{ url('admin/account/reports/'.$r['key']) }}" class="card h-100 text-decoration-none shadow-sm" style="border-radius:12px"><div class="card-body"><div class="fw-semibold text-dark">{{ $r['title'] }}</div><div class="small text-muted">{{ $r['description'] }}</div></div></a>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
