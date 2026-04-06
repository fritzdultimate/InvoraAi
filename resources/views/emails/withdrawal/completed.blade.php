@extends('emails.layouts.app')

@section('content')
<tr>
<td align="center" style="padding:20px 10px;background:#020617;">
    
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;margin:0 auto;background:#0f172a;border-radius:14px;border:1px solid rgba(255,255,255,0.05);">
        
        <!-- HEADER -->
        <tr>
            <td style="padding:24px 20px;text-align:center;">
                <span style="display:inline-block;background:rgba(59,130,246,0.12);color:#3b82f6;font-size:11px;padding:6px 12px;border-radius:999px;font-weight:600;letter-spacing:0.6px;">
                    WITHDRAWAL COMPLETED
                </span>

                <h1 style="color:#ffffff;font-size:24px;font-weight:700;margin:16px 0 8px;line-height:1.3;">
                    Funds Sent Successfully
                </h1>

                <p style="color:#94a3b8;font-size:14px;line-height:1.6;margin:0 auto;max-width:460px;">
                    Your withdrawal request has been processed successfully. The funds have been sent to your selected wallet address.
                </p>
            </td>
        </tr>

        <!-- AMOUNT HERO -->
        <tr>
            <td align="center" style="padding:20px 0;">
                <table cellpadding="0" cellspacing="0" border="0" style="background:#020617;border:1px solid rgba(255,255,255,0.05);border-radius:16px;width:90%;max-width:320px;margin:auto;">
                    <tr>
                        <td style="padding:20px;text-align:center;">
                            <div style="color:#64748b;font-size:12px;letter-spacing:1px;">WITHDRAWAL AMOUNT</div>
                            <div style="color:#3b82f6;font-size:32px;font-weight:800;margin-top:6px;font-family:monospace;">
                                ${{ number_format($amount,2) }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- FEE -->
        <tr>
            <td align="center" style="padding:10px 0 20px;">
                <table cellpadding="0" cellspacing="0" border="0" style="background:#020617;border:1px solid rgba(239,68,68,0.4);border-radius:16px;width:90%;max-width:320px;margin:auto;">
                    <tr>
                        <td style="padding:16px;text-align:center;">
                            <div style="color:#64748b;font-size:12px;letter-spacing:1px;">
                                PROCESSING FEE ({{ $feePercent }}%)
                            </div>
                            <div style="color:#ef4444;font-size:24px;font-weight:700;margin-top:6px;font-family:monospace;">
                                -${{ number_format($fee,2) }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- NET RECEIVED -->
        <tr>
            <td align="center" style="padding:0 0 20px;">
                <div style="color:#22c55e;font-size:16px;font-weight:600;">
                    You received: ${{ number_format($amount - $fee, 2) }}
                </div>
            </td>
        </tr>

        <!-- TRANSACTION DETAILS -->
        <tr>
            <td align="center" style="padding:0 20px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#020617;border-radius:12px;border:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <td style="padding:16px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                @php
                                    $details = [
                                        'Transaction ID' => $trx,
                                        'Payment Method' => $method,
                                        'Date' => $date,
                                        'Status' => 'Completed'
                                    ];
                                @endphp

                                @foreach($details as $label => $value)
                                    <tr>
                                        <td style="color:#64748b;font-size:12px;padding:6px 0;">{{ $label }}</td>
                                        <td align="right" style="color:{{ $label=='Status'?'#22c55e':'#e2e8f0' }};font-size:10px;font-weight:{{ $label=='Status'?'600':'400' }};">
                                            {{ $label=='Payment Method' ? strtoupper($value) : $value }}
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- CTA -->
        <tr>
            <td align="center" style="padding:16px 20px;">
                <a href="{{ $url }}" style="background:linear-gradient(135deg,#3b82f6,#6366f1);padding:12px 26px;border-radius:8px;color:#ffffff;font-weight:600;text-decoration:none;display:inline-block;font-size:14px;">
                    View Transactions
                </a>
            </td>
        </tr>

        <!-- FOOTNOTE -->
        <tr>
            <td align="center" style="padding:16px 20px 24px;color:#64748b;font-size:12px;line-height:1.5;text-align:center;">
                This withdrawal was securely processed by InvoraAI.<br>
                If you did not authorize this transaction, contact support immediately.
            </td>
        </tr>

    </table>
</td>
</tr>

<style type="text/css">
@media only screen and (max-width:480px){
    h1{font-size:20px !important;}
    td, p{font-size:13px !important;}
    a{padding:10px 20px !important;}
}
</style>

@endsection