<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\BotInvestmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\WalletLedger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $license = $user->botLicenses()
            ->orderByRaw('TIMESTAMPDIFF(SECOND, starts_at, expires_at) DESC')
            ->first();

        $investments = $user->botInvestments();
        $active = $user->botInvestments()->where('status', BotInvestmentStatus::ACTIVE);

        $recent = WalletLedger::where('user_id', $user->id)
            ->visibleToUser()
            ->latest('id')
            ->limit(5)
            ->get();

        return response()->json([
            'balances' => [
                'total' => (float) $user->total_balance,
                'main' => (float) $user->main_balance,
                'deposit' => (float) $user->deposit_balance,
                'profit' => (float) $user->profit_balance,
                'referral' => (float) $user->referral_balance,
                'locked' => (float) $user->locked_balance,
                'deposit_bonus' => (float) $user->deposit_bonus_balance,
            ],
            'bot' => [
                'active' => $user->hasActiveLicense(),
                'license_expires_at' => $license?->expires_at?->toIso8601String(),
            ],
            'stats' => [
                'total_investments' => $investments->count(),
                'active_investments' => (clone $active)->count(),
                'active_capital' => (float) (clone $active)->sum('amount'),
                'total_profit' => (float) $user->botInvestments()->sum('total_profit'),
            ],
            'chart' => $this->chart($user->id),
            'recent_transactions' => TransactionResource::collection($recent),
        ]);
    }

    public function transactions(Request $request)
    {
        $request->validate([
            'type' => ['nullable', 'in:credit,debit'],
        ]);

        $transactions = WalletLedger::where('user_id', $request->user()->id)
            ->visibleToUser()
            ->when($request->type === 'credit', fn ($q) => $q->where('credit', '>', 0))
            ->when($request->type === 'debit', fn ($q) => $q->where('debit', '>', 0))
            ->latest('id')
            ->paginate(20);

        return TransactionResource::collection($transactions);
    }

    private function chart(int $userId): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => Carbon::now()->subDays($i)->format('Y-m-d'));

        $data = WalletLedger::where('user_id', $userId)
            ->visibleToUser()
            ->where('created_at', '>=', now()->subDays(7)->startOfDay())
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(credit) as total_credit'),
                DB::raw('SUM(debit) as total_debit')
            )
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        return [
            'labels' => $days->values(),
            'credits' => $days->map(fn ($d) => (float) ($data[$d]->total_credit ?? 0))->values(),
            'debits' => $days->map(fn ($d) => (float) ($data[$d]->total_debit ?? 0))->values(),
        ];
    }
}
