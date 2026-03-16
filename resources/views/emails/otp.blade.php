@extends('emails.layouts.app')

@section('contents')
    <tr>
        <td style="padding:30px;">

            <h2 style="color:#fff;margin:0;">
                Your Verification Code
            </h2>

            <p style="color:#94a3b8;margin-top:10px;">
                Use the verification code below to complete your authentication.
            </p>

            @php
                $otp = '22222';
            @endphp

            <table width="100%" style="margin-top:20px;">
                <tr>
                    <td align="center">

                        <div style="
                            font-size:34px;
                            letter-spacing:6px;
                            font-weight:700;
                            color:#22c55e;
                            background:#020617;
                            padding:16px 20px;
                            border-radius:10px;
                            border:1px solid rgba(255,255,255,0.05);
                            display:inline-block;"
                        >

                            {{ $otp }}

                        </div>

                    </td>
                </tr>
            </table>

            <p style="color:#64748b;font-size:12px;margin-top:20px;">
                This code expires in 10 minutes.
            </p>

        </td>
    </tr>
@endsection