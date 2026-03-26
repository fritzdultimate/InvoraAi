@extends('emails.layouts.app')

@section('content')

    <tr>
        <td style="padding:40px 28px;">

            <span
                style="background:rgba(239,68,68,0.12);color:#ef4444;font-size:11px;padding:6px 12px;border-radius:999px;">
                SECURITY ALERT
            </span>

            <h1 style="color:#fff;margin-top:16px;font-size:24px;">
                New Login Detected
            </h1>

            <p style="color:#94a3b8;margin-top:10px;">
                We detected a new login to your account. If this was not you, secure your account immediately.
            </p>

            <table width="100%"
                style="margin-top:25px;background:#020617;border-radius:12px;padding:20px;border:1px solid rgba(255,255,255,0.05);">
                <tr>
                    <td>

                        <p style="color:#e2e8f0;"><strong>Location:</strong> {{ $location }}</p>
                        <p style="color:#e2e8f0;"><strong>Device:</strong> {{ $device }}</p>
                        <p style="color:#e2e8f0;"><strong>Time:</strong> {{ $time }}</p>

                    </td>
                </tr>
            </table>

            <div style="text-align:center;margin-top:25px;">
                <a href="{{ $url }}"
                    style="background:#ef4444;color:#fff;padding:12px 26px;border-radius:8px;text-decoration:none;">
                    Secure Account
                </a>
            </div>

        </td>
    </tr>

@endsection