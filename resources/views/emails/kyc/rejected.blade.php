@extends('emails.layouts.app')

@section('content')
<tr>
<td align="center" style="padding:20px 10px;background:#020617;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;margin:0 auto;background:#0f172a;border-radius:14px;border:1px solid rgba(255,255,255,0.05);">

        <!-- HEADER -->
        <tr>
            <td style="padding:24px 20px;text-align:center;">
                <span style="display:inline-block;background:rgba(239,68,68,0.12);color:#ef4444;font-size:11px;padding:6px 12px;border-radius:999px;font-weight:600;letter-spacing:0.6px;">
                    KYC REJECTED
                </span>

                <h1 style="color:#ffffff;font-size:24px;font-weight:700;margin:16px 0 8px;line-height:1.3;">
                    Identity Verification Unsuccessful
                </h1>

                <p style="color:#94a3b8;font-size:14px;line-height:1.6;margin:0 auto;max-width:460px;">
                    Unfortunately, your KYC submission did not meet our verification requirements. Don’t worry — you can resubmit your documents securely to complete verification and unlock full platform features.
                </p>
            </td>
        </tr>

        <!-- STATUS CARD -->
        <tr>
            <td align="center" style="padding:20px 0;">
                <table cellpadding="0" cellspacing="0" border="0" style="background:#020617;border:1px solid rgba(239,68,68,0.4);border-radius:16px;width:90%;max-width:320px;margin:auto;">
                    <tr>
                        <td style="padding:20px;text-align:center;">
                            <div style="color:#64748b;font-size:12px;letter-spacing:1px;">VERIFICATION STATUS</div>
                            <div style="color:#ef4444;font-size:22px;font-weight:700;margin-top:6px;">
                                REJECTED ❌
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- REASON -->
        @if($reason)
        <tr>
            <td align="center" style="padding:0 20px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#020617;border-radius:12px;border:1px solid rgba(239,68,68,0.2);">
                    <tr>
                        <td style="padding:16px;text-align:center;color:#e2e8f0;font-size:13px;">
                            <strong>Reason for Rejection:</strong><br>
                            <span style="color:#ef4444;">{{ $reason }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endif

        <!-- NEXT STEPS -->
        <tr>
            <td align="center" style="padding:0 20px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#020617;border-radius:12px;border:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <td style="padding:16px;color:#94a3b8;font-size:13px;">
                            You can now:
                            <ul style="padding-left:16px;color:#e2e8f0;">
                                <li>Review the rejection reason carefully</li>
                                <li>Prepare your documents according to the guidelines</li>
                                <li>Resubmit securely via the dashboard</li>
                                <li>Contact support if you need help</li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- CTA -->
        <tr>
            <td align="center" style="padding:16px 20px;">
                <a href="{{ $url }}" style="background:linear-gradient(135deg,#ef4444,#f87171);padding:12px 26px;border-radius:8px;color:#ffffff;font-weight:600;text-decoration:none;display:inline-block;font-size:14px;">
                    Resubmit Documents
                </a>
            </td>
        </tr>

        <!-- FOOTNOTE -->
        <tr>
            <td align="center" style="padding:16px 20px 24px;color:#64748b;font-size:12px;line-height:1.5;text-align:center;">
                Your security is our priority.<br>
                If you believe this rejection was in error, please contact support immediately.
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