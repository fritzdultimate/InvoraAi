@extends('emails.layouts.app')

@section('content')

    <tr>
        <td style="padding:40px 28px;">

            <!-- TOP BADGE -->
            <div style="margin-bottom:18px;">
                <span style="
                background:rgba(34,197,94,0.12);
                color:#22c55e;
                font-size:11px;
                padding:6px 12px;
                border-radius:999px;
                font-weight:600;
                letter-spacing:0.5px;
            ">
                    SECURITY VERIFICATION
                </span>
            </div>

            <!-- TITLE -->
            <h1 style="
            margin:0;
            color:#ffffff;
            font-size:24px;
            font-weight:700;
            letter-spacing:-0.3px;
        ">
                Confirm Your Identity
            </h1>

            <!-- MESSAGE -->
            <p style="
            color:#94a3b8;
            margin-top:12px;
            font-size:14px;
            line-height:1.7;
            max-width:420px;
        ">
                For your security, please use the verification code below to continue.
                This helps us ensure that it's really you accessing your account.
            </p>

            <!-- 🔘 VERIFY BUTTON -->
            <table width="100%" style="margin-top:30px;">
                <tr>
                    <td align="center">

                        <a href="{{ $url }}" style="
                            display:inline-block;
                            padding:14px 28px;
                            border-radius:12px;
                            background:linear-gradient(135deg,#009A76,#22c55e);
                            color:#ffffff;
                            font-size:14px;
                            font-weight:600;
                            text-decoration:none;
                            box-shadow:0 10px 25px rgba(34,197,94,0.25);
                        ">
                            Verify My Identity
                        </a>

                    </td>
                </tr>
            </table>

            <!-- FALLBACK LINK -->
            <table width="100%" style="margin-top:18px;">
                <tr>
                    <td align="center" style="font-size:12px; color:#64748b; word-break:break-all;">
                        If the button doesn't work, copy and paste this link:<br>
                        <span style="color:#22c55e;">{{ $url }}</span>
                    </td>
                </tr>
            </table>

            <!-- INFO BAR -->
            <table width="100%" style="margin-top:28px;">
                <tr>
                    <td style="
                    background:#020617;
                    border:1px solid rgba(255,255,255,0.05);
                    border-radius:10px;
                    padding:14px 16px;
                    color:#94a3b8;
                    font-size:12px;
                    line-height:1.6;
                ">

                        🔒 This code will expire in <strong style="color:#ffffff;">10 minutes</strong><br>
                        If you did not request this, please secure your account immediately.

                    </td>
                </tr>
            </table>

        </td>
    </tr>

@endsection

