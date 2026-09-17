<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Services\DepositService;
use App\Services\NowPaymentsService;
use App\Services\NowPaymentsXService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NowPaymentsXController extends Controller {
   
    public function webhook(Request $request) {
        $rawPayload = $request->getContent();
        $signature = $request->header('x-nowpayments-sig');

        if (! $signature || ! NowPaymentsXService::verifySignature($rawPayload, $signature)) {
            Log::warning('NOWPayments webhook: rejected, missing or invalid signature.');

            return response('Invalid signature', 400);
        }

        $data = $request->json()->all();

        Log::info('NOWPayments returned data for audit.', [
            'payload' => $data,
        ]);
        // return;

        if (! isset($data['payment_id'], $data['payment_status'])) {
            Log::warning('NOWPayments webhook: rejected, missing payment_id/payment_status.', [
                'payload' => $data,
            ]);

            return response('Invalid payload', 400);
        }

        DB::transaction(function () use ($data) {
            $deposit = Deposit::where('nowpayments_invoice_id', $data['payment_id'])
                ->lockForUpdate()
                ->first();

            if (! $deposit) {
                Log::warning('NOWPayments webhook: no matching deposit for payment_id.', [
                    'payment_id' => $data['payment_id'],
                ]);

                return;
            }

            DepositService::applyPaymentUpdate($deposit, $data);
        });

        return response('OK', 200);
    }
}
