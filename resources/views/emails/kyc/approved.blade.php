@extends('emails.layouts.app')

@section('content')
<tr>
<td align="center" style="padding:20px 10px;background:#020617;">
    
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;margin:0 auto;background:#0f172a;border-radius:14px;border:1px solid rgba(255,255,255,0.05);">
        
        <!-- HEADER -->
        <tr>
            <td style="padding:24px 20px;text-align:center;">
                <span style="display:inline-block;background:rgba(34,197,94,0.12);color:#22c55e;font-size:11px;padding:6px 12px;border-radius:999px;font-weight:600;letter-spacing:0.6px;">
                    KYC VERIFIED
                </span>

                <h1 style="color:#ffffff;font-size:24px;font-weight:700;margin:16px 0 8px;line-height:1.3;">
                    Identity Successfully Verified
                </h1>

                <p style="color:#94a3b8;font-size:14px;line-height:1.6;margin:0 auto;max-width:460px;">
                    Your identity verification has been completed successfully. You now have full access to all platform features including deposits, withdrawals, and advanced trading tools.
                </p>
            </td>
        </tr>

        <!-- STATUS CARD -->
        <tr>
            <td align="center" style="padding:20px 0;">
                <table cellpadding="0" cellspacing="0" border="0" style="background:#020617;border:1px solid rgba(34,197,94,0.4);border-radius:16px;width:90%;max-width:320px;margin:auto;">
                    <tr>
                        <td style="padding:20px;text-align:center;">
                            <div style="color:#64748b;font-size:12px;letter-spacing:1px;">VERIFICATION STATUS</div>
                            <div style="color:#22c55e;font-size:22px;font-weight:700;margin-top:6px;">
                                APPROVED ✅
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- USER INFO -->
        <tr>
            <td align="center" style="padding:0 20px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#020617;border-radius:12px;border:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <td style="padding:16px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                @php
                                    $details = [
                                        'User' => $user->name,
                                        'Email' => $user->email,
                                        'Verification Date' => now()->format('M d, Y H:i'),
                                        'Status' => 'Approved'
                                    ];
                                @endphp

                                @foreach($details as $label => $value)
                                    <tr>
                                        <td style="color:#64748b;font-size:12px;padding:6px 0;">{{ $label }}</td>
                                        <td align="right" style="color:{{ $label=='Status'?'#22c55e':'#e2e8f0' }};font-size:12px;font-weight:{{ $label=='Status'?'600':'400' }};">
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

        <!-- BENEFITS -->
        <tr>
            <td align="center" style="padding:0 20px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#020617;border-radius:12px;border:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <td style="padding:16px;">
                            <div style="color:#94a3b8;font-size:13px;margin-bottom:8px;">
                                You can now:
                            </div>
                            <ul style="color:#e2e8f0;font-size:13px;padding-left:16px;margin:0;">
                                <li>Withdraw funds securely</li>
                                <li>Access full AI trading features</li>
                                <li>Increase account limits</li>
                                <li>Enjoy uninterrupted platform usage</li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- CTA -->
        <tr>
            <td align="center" style="padding:16px 20px;">
                <a href="{{ $url ?? '#' }}" style="background:linear-gradient(135deg,#009A76,#22c55e);padding:12px 26px;border-radius:8px;color:#ffffff;font-weight:600;text-decoration:none;display:inline-block;font-size:14px;">
                    Go to Dashboard
                </a>
            </td>
        </tr>

        <!-- FOOTNOTE -->
        <tr>
            <td align="center" style="padding:16px 20px 24px;color:#64748b;font-size:12px;line-height:1.5;text-align:center;">
                Your account is now fully verified and secured.<br>
                If you did not initiate this verification, please contact support immediately.
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