<?php

namespace App\Http\Controllers;

use App\Enums\DepositStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Mail\OtpNotification;
use App\Models\Deposit;
use App\Services\DepositService;
use App\Services\NowPaymentsService;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NowPaymentsController extends Controller {
    public function webhook(Request $req) {
        $payload = $req->getContent();
        $signature = $req->header('x-nowpayments-sig');

        if (!$signature) {
            return response('Invalid signature', 400);
        }

        if (!NowPaymentsService::verifySignature($payload, $signature)) {
            \Log::info('Invalid signature');
            return response('Invalid signature', 400);
            
        }

        $data = $req->json()->all();
        $orderId = $data['order_id'] ?? null;

        if (! isset($data['payment_id'])) {
            return response('Invalid payload', 400);
        }



        DB::transaction(function() use ($orderId, $data) {
            $deposit = Deposit::where('nowpayments_invoice_id', $data['parent_payment_id'])->lockForUpdate()->first();
            \Log::info("I got the data" . json_encode($data));
            if (!$deposit) return;

            \Log::info("I got the data" . json_encode($deposit));
            return;

            $allowedStatuses = [
                DepositStatus::PENDING->value => ['waiting', 'confirming'],
                DepositStatus::CONFIRMED->value => ['finished'],
            ];
            $status = $data['payment_status'];
            $currentStatus = $deposit->status->value;

            // if (isset($allowedStatuses[$currentStatus]) && ! in_array($status, $allowedStatuses[$currentStatus])) {
            //     return;
            // }
            
            $deposit->status = $status;
            $deposit->tx_id = $data['pay_address'] ?? $data['tx_hash'] ?? $deposit->tx_id;
            $deposit->confirmations = $data['confirmations'] ?? $deposit->confirmations;
            $deposit->meta = $data;
            $deposit->save();

            if ($deposit->processed_at) {
                return;
            }

            if ($deposit->status === DepositStatus::FINISHED) {
                $paidAmount = (float) ($data['actually_paid'] ?? 0);

                $deposit->update([
                    'processed_at' => now(),
                    'amount_paid' => $paidAmount
                ]);

                
                if ($paidAmount > 0) {
                    $user = $deposit->user()->lockForUpdate()->first();

                    WalletService::credit(
                        $user,
                        $paidAmount,
                        LedgerReference::DEPOSIT,
                        $deposit->id,
                        null,
                        LedgerAsset::DEPOSIT
                    );

                    DepositService::depositBonus($deposit);
                }

            }
        });

        return response('OK', 200);
    }
}
