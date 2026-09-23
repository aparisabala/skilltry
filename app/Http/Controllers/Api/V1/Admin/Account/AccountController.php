<?php

namespace App\Http\Controllers\Api\V1\Admin\Account;

use App\Http\Controllers\Api\ApiController as Controller;
use App\Http\Requests\Api\V1\Admin\Account\PostTransactionRequest;
use App\Http\Requests\Api\V1\Admin\Account\StoreLedgerRequest;
use App\Http\Requests\Api\V1\Admin\Account\UpdateLedgerRequest;
use App\Models\AcLedger;
use App\Support\Account\AccountPosting;
use App\Support\Account\AccountReports;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * A consolidated, mobile-friendly account API: ledgers with a computed live balance, the ledger statement and day book reports
 * run directly, and a single endpoint to post a receive / pay / deposit / withdraw transaction straight through AccountPosting
 * (no draft step). The per-module admin panel mirrors (Ledger\Crud, Transaction\Crud\...) stay as they are for the admin panel.
 */
#[\Dedoc\Scramble\Attributes\Group('Account / Mobile API')]
class AccountController extends Controller
{
    private function withBalance(AcLedger $l): AcLedger
    {
        $l->balance = in_array($l->ledger_type, ['cash', 'bank'], true) ? AccountPosting::balance($l->id) : null;
        return $l;
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'List ledgers with their live balance')]
    #[\Dedoc\Scramble\Attributes\QueryParameter('type', type: 'string', required: false, description: 'asset, cash, bank, income or expense')]
    #[\Dedoc\Scramble\Attributes\QueryParameter('status', type: 'string', required: false, description: 'Active or Off')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response.', type: 'array{success: bool, data: mixed}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function ledgers(Request $request): JsonResponse
    {
        $request->validate(['type' => 'nullable|in:asset,cash,bank,income,expense', 'status' => 'nullable|in:Active,Off']);
        $rows = AcLedger::when($request->query('type'), fn($q, $v) => $q->where('ledger_type', $v))->when($request->query('status'), fn($q, $v) => $q->where('status', $v))
            ->orderBy('ledger_type')->orderBy('serial')->orderBy('name')->get()->map(fn($l) => $this->withBalance($l));
        return response()->json(['success' => true, 'data' => $rows]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Get a ledger with its live balance')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response.', type: 'array{success: bool, data: mixed}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(404, description: 'Ledger not found', type: 'array{success: bool, message: string}')]
    public function showLedger(int $id): JsonResponse
    {
        $l = AcLedger::find($id);
        if (!$l) {
            return response()->json(['success' => false, 'message' => 'Ledger not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $this->withBalance($l)]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Create a ledger')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('name', type: 'string', required: true)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ledger_type', type: 'string', required: true, description: 'asset, cash, bank, income or expense')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('note', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('opening_balance', type: 'number', required: false)]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response.', type: 'array{success: bool, data: mixed}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function storeLedger(StoreLedgerRequest $request): JsonResponse
    {
        $l = AcLedger::create($request->validated() + ['serial' => (int) AcLedger::max('serial') + 1]);
        return response()->json(['success' => true, 'data' => $this->withBalance($l->fresh())], 201);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Update a ledger (its type stays)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('name', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('note', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('opening_balance', type: 'number', required: false)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('status', type: 'string', required: false, description: 'Active or Off')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response.', type: 'array{success: bool, data: mixed}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(404, description: 'Ledger not found', type: 'array{success: bool, message: string}')]
    public function updateLedger(UpdateLedgerRequest $request, int $id): JsonResponse
    {
        $l = AcLedger::find($id);
        if (!$l) {
            return response()->json(['success' => false, 'message' => 'Ledger not found'], 404);
        }
        $l->update($request->validated());
        return response()->json(['success' => true, 'data' => $this->withBalance($l->fresh())]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Ledger statement')]
    #[\Dedoc\Scramble\Attributes\QueryParameter('date_from', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\QueryParameter('date_to', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response.', type: 'array{success: bool, data: mixed}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(404, description: 'Ledger not found', type: 'array{success: bool, message: string}')]
    public function statement(Request $request, int $id): JsonResponse
    {
        $request->validate(['date_from' => 'nullable|date', 'date_to' => 'nullable|date']);
        if (!AcLedger::find($id)) {
            return response()->json(['success' => false, 'message' => 'Ledger not found'], 404);
        }
        return response()->json(['success' => true, 'data' => AccountReports::run('ledger_statement', ['ledger_id' => $id] + $request->query())]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Day book')]
    #[\Dedoc\Scramble\Attributes\QueryParameter('date_from', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\QueryParameter('date_to', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\QueryParameter('ledger_type', type: 'string', required: false, description: 'cash or bank')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response.', type: 'array{success: bool, data: mixed}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function transactions(Request $request): JsonResponse
    {
        $request->validate(['date_from' => 'nullable|date', 'date_to' => 'nullable|date', 'ledger_type' => 'nullable|in:cash,bank']);
        return response()->json(['success' => true, 'data' => AccountReports::run('day_book', $request->query())]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Post a transaction directly (no draft step)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('kind', type: 'string', required: true, description: 'receive, pay, deposit or withdraw')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('method', type: 'string', required: false, description: 'cash or bank; required for receive/pay')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ledger_id', type: 'int', required: true)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('counter_ledger_id', type: 'int', required: true)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('tran_date', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('items', type: 'array', required: true)]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response.', type: 'array{success: bool, data?: mixed, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(422, description: 'Invalid, inactive or insufficient ledger', type: 'array{success: bool, message: string}')]
    public function post(PostTransactionRequest $request): JsonResponse
    {
        $d = $request->validated();
        if (in_array($d['kind'], ['receive', 'pay'], true)) {
            $r = AccountPosting::post([
                'tran_type' => $d['method'], 'tran_method' => $d['kind'] === 'receive' ? 'income' : 'expense', 'cash_ledger_id' => $d['ledger_id'], 'counter_ledger_id' => $d['counter_ledger_id'],
                'tran_date' => $d['tran_date'] ?? null, 'items' => $d['items'], 'source' => 'api',
            ]);
        } else {
            $r = AccountPosting::contra(['from_ledger_id' => $d['ledger_id'], 'to_ledger_id' => $d['counter_ledger_id'], 'tran_date' => $d['tran_date'] ?? null, 'items' => $d['items']]);
        }
        if (!$r['ok']) {
            return response()->json(['success' => false, 'message' => $r['error']], 422);
        }
        return response()->json(['success' => true, 'data' => ['cashbook_ids' => $r['cashbook_ids'] ?? [$r['cashbook_id']], 'amount' => $r['amount']]], 201);
    }
}
