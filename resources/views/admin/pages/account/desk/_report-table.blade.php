@php $money = fn($n) => number_format((float) $n, 2); @endphp
<div class="table-responsive">
    <table class="table table-bordered table-sm align-middle">
        <thead class="table-light"><tr>@foreach($report['columns'] as $c)<th class="{{ in_array($c['type'], ['int', 'money', 'qty']) ? 'text-end' : '' }}">{{ $c['label'] }}</th>@endforeach</tr></thead>
        <tbody>
            @forelse($report['rows'] as $row)
                <tr>
                    @foreach($report['columns'] as $field => $c)
                        @php $val = $row[$field] ?? null; @endphp
                        <td class="{{ in_array($c['type'], ['int', 'money', 'qty']) ? 'text-end' : '' }}">
                            @if($val === null || $val === '') @elseif($c['type'] == 'money'){{ $money($val) }}
                            @elseif($c['type'] == 'int'){{ $val + 0 }}
                            @elseif($c['type'] == 'qty'){{ rtrim(rtrim(number_format((float) $val, 2, '.', ''), '0'), '.') }}
                            @elseif($c['type'] == 'date'){{ \Carbon\Carbon::parse($val)->format('d M Y') }}
                            @elseif($c['type'] == 'badge')<span class="badge {{ in_array($val, ['Unpaid', 'Voided', 'Deficit', 'Off']) ? 'bg-danger' : (in_array($val, ['Open', 'Payment', 'Expense', 'Transfer']) ? 'bg-warning text-dark' : (in_array($val, ['Paid', 'Active', 'Final', 'Surplus', 'Receipt', 'Income']) ? 'bg-success' : 'bg-secondary')) }}">{{ $val }}</span>
                            @else{{ $val }}@endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ count($report['columns']) }}" class="text-center text-muted py-4">No data for these filters</td></tr>
            @endforelse
        </tbody>
        @if($report['totals'])
            <tfoot class="table-light"><tr>
                @foreach($report['columns'] as $field => $c)
                    <th class="{{ in_array($c['type'], ['int', 'money', 'qty']) ? 'text-end' : '' }}">@if($loop->first)Total @endif @if(isset($report['totals'][$field])){{ $c['type'] == 'money' ? $money($report['totals'][$field]) : ($report['totals'][$field] + 0) }}@endif</th>
                @endforeach
            </tr></tfoot>
        @endif
    </table>
</div>
