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

            <!-- OTP BLOCK -->
            <table width="100%" style="margin-top:30px;">
                <tr>
                    <td align="center">

                        <!-- OUTER GLOW WRAP -->
                        <div style="
                        display:inline-block;
                        padding:2px;
                        border-radius:16px;
                        background:linear-gradient(135deg,#009A76,#22c55e);
                    ">

                            <!-- INNER CARD -->
                            <div style="
                            background:#020617;
                            border-radius:14px;
                            padding:24px 34px;
                            text-align:center;
                            border:1px solid rgba(255,255,255,0.05);
                        ">

                                <!-- OTP -->
                                <div style="
                                font-size:38px;
                                letter-spacing:12px;
                                font-weight:800;
                                color:#22c55e;
                                font-family:monospace;
                            ">
                                    466322
                                </div>

                                <!-- LABEL -->
                                <div style="
                                margin-top:10px;
                                font-size:11px;
                                color:#64748b;
                                letter-spacing:1px;
                            ">
                                    ONE-TIME PASSWORD
                                </div>

                            </div>
                        </div>

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

