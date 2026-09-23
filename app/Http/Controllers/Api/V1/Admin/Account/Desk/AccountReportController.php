<?php

namespace App\Http\Controllers\Api\V1\Admin\Account\Desk;

use App\Http\Controllers\Api\ApiController as Controller;
use App\Models\AcLedger;
use App\Support\Account\AccountReports;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Account reports: the list, one report with filters and CSV export.
 * A report needs the ac_report_view / ac_report_excel permission.
 */
#[\Dedoc\Scramble\Attributes\Group('Account / Reports')]
class AccountReportController extends Controller
{
    private function can(string $slug): bool
    {
        return Gate::forUser(auth('admin')->user())->allows($slug);
    }

    private function load(Request $request, string $key, string $excel)
    {
        abort_unless(isset(AccountReports::CATALOGUE[$key]), 404);
        $perm = AccountReports::CATALOGUE[$key][4];
        abort_unless($this->can($excel === 'excel' ? str_replace('_view', '_excel', $perm) : $perm), 403);
        return AccountReports::run($key, $request->query());
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'List the report catalogue')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response.', type: 'array{success: bool, data: mixed}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(): JsonResponse
    {
        $list = AccountReports::catalogue(fn($p) => $this->can($p));
        return response()->json(['success' => true, 'data' => collect($list)->groupBy('group')]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Run one report')]
    #[\Dedoc\Scramble\Attributes\QueryParameter('date_from', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\QueryParameter('date_to', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\QueryParameter('ledger_id', type: 'int', required: false)]
    #[\Dedoc\Scramble\Attributes\QueryParameter('ledger_type', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response.', type: 'array{success: bool, data: mixed}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function show(Request $request, string $key): JsonResponse
    {
        $report = $this->load($request, $key, 'view');
        return response()->json(['success' => true, 'data' => [
            'report' => $report,
            'ledgers' => AcLedger::orderBy('ledger_type')->orderBy('name')->get(['id', 'name', 'ledger_type']),
        ]]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Export one report as CSV')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'CSV stream')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function export(Request $request, string $key): StreamedResponse
    {
        $report = $this->load($request, $key, 'excel');
        return response()->streamDownload(function () use ($report) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, array_column($report['columns'], 'label'));
            foreach ($report['rows'] as $row) {
                fputcsv($out, array_map(fn($field) => $row[$field] ?? '', array_keys($report['columns'])));
            }
            if ($report['totals']) {
                $first = array_key_first($report['columns']);
                fputcsv($out, array_map(fn($field) => $field === $first ? 'Total' : ($report['totals'][$field] ?? ''), array_keys($report['columns'])));
            }
            fclose($out);
        }, 'account_'.$key.'_'.now()->format('Ymd_His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
