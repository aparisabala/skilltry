<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><title>{{ $data['report']['title'] }}</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>body{background:#eef1f5;font-size:11px}.sheet{background:#fff;margin:14px auto;padding:12mm;max-width:297mm}.tb{max-width:297mm;margin:12px auto 0;text-align:right}
@media print{body{background:#fff}.tb{display:none}.sheet{margin:0;padding:0;max-width:none}@page{size:A4 landscape;margin:10mm}}</style></head>
<body>
@php $r = $data['report']; $f = $r['filters']; $apply = $data['meta'][3]; @endphp
<div class="tb"><button class="btn btn-primary btn-sm" onclick="window.print()">Print</button></div>
<div class="sheet">
    <div class="d-flex align-items-center mb-2">
        <img src="{{ config('i.favicon') }}" style="width:40px" class="me-2" alt="">
        <div><div style="font-size:16px;font-weight:700">{{ config('i.service_name') }}</div><div>{{ config('i.address') }}</div></div>
        <div class="ms-auto text-end"><div class="fw-bold fs-6">{{ $r['title'] }}</div>
            @if(in_array('date', $apply))<div>{{ \Carbon\Carbon::parse($f['date_from'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($f['date_to'])->format('d M Y') }}</div>@endif
        </div>
    </div><hr class="my-2">
    @include('admin.pages.account.desk._report-table', ['report' => $r])
    <p class="text-muted mt-3" style="font-size:10px">Printed by {{ Auth::user()->name }} / {{ now()->format('d M Y h:i A') }}</p>
</div></body></html>
