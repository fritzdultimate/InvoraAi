@extends('emails.layouts.app')

@section('content')
<tr>
<td align="center" style="padding:20px 10px;background:#020617;">
    <!-- MAIN CONTAINER -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;margin:0 auto;background:#0f172a;border-radius:14px;border:1px solid rgba(255,255,255,0.05);">
        
        <!-- HEADER -->
        <tr>
            <td style="padding:24px 20px;text-align:center;">
                <span style="display:inline-block;background:rgba(239,68,68,0.12);color:#ef4444;font-size:11px;padding:6px 12px;border-radius:999px;font-weight:600;letter-spacing:0.6px;">
                    DEPOSIT EXPIRED
                </span>

                <h1 style="color:#ffffff;font-size:24px;font-weight:700;margin:16px 0 8px;line-height:1.3;">
                    Deposit Not Completed
                </h1>

                <p style="color:#94a3b8;font-size:14px;line-height:1.6;margin:0 auto;max-width:460px;">
                    Your deposit has expired because it was not completed within the required timeframe. The funds were not credited to your {{ config('app.name') }} account.
                </p>
            </td>
        </tr>

        <!-- AMOUNT HERO -->
        <tr>
            <td align="center" style="padding:20px 0;">
                <table cellpadding="0" cellspacing="0" border="0" style="background:#020617;border:1px solid rgba(239,68,68,0.4);border-radius:16px;width:90%;max-width:320px;margin:auto;">
                    <tr>
                        <td style="padding:20px;text-align:center;">
                            <div style="color:#64748b;font-size:12px;letter-spacing:1px;">DEPOSIT AMOUNT</div>
                            <div style="color:#ef4444;font-size:32px;font-weight:800;margin-top:6px;font-family:monospace;">
                                ${{ number_format($amount,2) }}
                            </div>
                        </td>
                    </tr>
                </table>
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
                                        'Status' => 'Expired'
                                    ];
                                @endphp
                                @foreach($details as $label => $value)
                                    <tr>
                                        <td style="color:#64748b;font-size:12px;padding:6px 0;">{{ $label }}</td>
                                        <td align="right" style="color:{{ $label=='Status'?'#ef4444':'#e2e8f0' }};font-size:13px;font-weight:{{ $label=='Status'?'600':'400' }};">
                                            {{ $value }}
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
                <a href="{{ $url }}" style="background:linear-gradient(135deg,#ef4444,#f87171);padding:12px 26px;border-radius:8px;color:#ffffff;font-weight:600;text-decoration:none;display:inline-block;font-size:14px;">
                    Go to Dashboard
                </a>
            </td>
        </tr>

        <!-- FOOTNOTE -->
        <tr>
            <td align="center" style="padding:16px 20px 24px;color:#64748b;font-size:12px;line-height:1.5;text-align:center;">
                This deposit was not completed in time and has expired.<br>
                If you need assistance, please contact support immediately.
            </td>
        </tr>

    </table>
</td>
</tr>

<!-- RESPONSIVE MEDIA -->
<style type="text/css">
    @media only screen and (max-width:480px){
        h1{font-size:20px !important;}
        td, p{font-size:13px !important;}
        a{padding:10px 20px !important;}
    }
</style>

@endsection