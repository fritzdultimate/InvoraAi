@extends('emails.layouts.app')

@section('content')

    <tr>
        <td style="padding:40px 28px;">

            <span
                style="background:rgba(245,158,11,0.12);color:#f59e0b;font-size:11px;padding:6px 12px;border-radius:999px;">
                WITHDRAWAL INITIATED
            </span>

            <h1 style="color:#fff;margin-top:16px;font-size:24px;">
                Withdrawal Request Submitted
            </h1>

            <p style="color:#94a3b8;margin-top:10px;">
                Your withdrawal request is currently being reviewed for processing.
            </p>

            <table width="100%"
                style="margin-top:25px;background:#020617;border-radius:12px;padding:20px;border:1px solid rgba(255,255,255,0.05);">
                <tr>
                    <td>

                        <p style="color:#e2e8f0;"><strong>Amount:</strong> ${{ $amount }}</p>
                        <p style="color:#e2e8f0;"><strong>Wallet:</strong> {{ $wallet }}</p>
                        <p style="color:#f59e0b;"><strong>Status:</strong> Pending</p>

                    </td>
                </tr>
            </table>

        </td>
    </tr>

@endsection