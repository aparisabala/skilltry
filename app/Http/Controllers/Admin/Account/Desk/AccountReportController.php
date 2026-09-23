<?php

namespace App\Http\Controllers\Admin\Account\Desk;

use App\Http\Controllers\Controller;
use App\Models\AcLedger;
use App\Support\Account\AccountReports;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Account reports: the list, one report with filters (and a print view) and CSV export.
 * A report needs the ac_report_view / ac_report_excel permission.
 */
class AccountReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'HasAdminUserPassword', 'HasAdminUserAuth']);
    }

    private function can(string $slug): bool
    {
        return Gate::forUser(auth('admin')->user())->allows($slug);
    }

    public function index(): View
    {
        $list = AccountReports::catalogue(fn($p) => $this->can($p));
        abort_if(empty($list), 403);
        $data = ['catalogue' => collect($list)->groupBy('group')];
        return view('admin.pages.account.desk.report-index', compact('data'));
    }

    private function load(Request $request, string $key, string $excel)
    {
        abort_unless(isset(AccountReports::CATALOGUE[$key]), 404);
        $perm = AccountReports::CATALOGUE[$key][4];
        abort_unless($this->can($excel === 'excel' ? str_replace('_view', '_excel', $perm) : $perm), 403);
        return AccountReports::run($key, $request->query());
    }

    public function show(Request $request, string $key): View
    {
        $report = $this->load($request, $key, 'view');
        $data = [
            'report' => $report, 'meta' => AccountReports::CATALOGUE[$key], 'print' => (bool) $request->query('print'),
            'canExport' => $this->can(str_replace('_view', '_excel', AccountReports::CATALOGUE[$key][4])),
            'ledgers' => AcLedger::orderBy('ledger_type')->orderBy('name')->get(['id', 'name', 'ledger_type']),
        ];
        return view($data['print'] ? 'admin.pages.account.desk.report-print' : 'admin.pages.account.desk.report-show', compact('data'));
    }

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
