@extends('emails.layouts.app')

@section('content')
@php
    $tone = [
        'success' => ['rgba(34,197,94,0.12)', '#22c55e'],
        'danger' => ['rgba(239,68,68,0.12)', '#ef4444'],
        'warning' => ['rgba(245,158,11,0.12)', '#f59e0b'],
        'gray' => ['rgba(148,163,184,0.12)', '#94a3b8'],
    ][$order->status->color()] ?? ['rgba(99,102,241,0.14)', '#818cf8'];
@endphp
<tr>
<td align="center" style="padding:20px 10px;background:#020617;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;margin:0 auto;background:#0f172a;border-radius:14px;border:1px solid rgba(255,255,255,0.05);">
        <tr>
            <td style="padding:28px 24px 8px;text-align:center;">
                <span style="display:inline-block;background:{{ $tone[0] }};color:{{ $tone[1] }};font-size:11px;padding:6px 12px;border-radius:999px;font-weight:600;letter-spacing:0.6px;">
                    {{ strtoupper($order->status->label()) }}
                </span>
                <h1 style="color:#ffffff;font-size:24px;font-weight:700;margin:16px 0 8px;line-height:1.3;">{{ $headline }}</h1>
                <p style="color:#94a3b8;font-size:14px;line-height:1.6;margin:0 auto;max-width:460px;">{{ $text }}</p>
            </td>
        </tr>

        {{-- the card itself --}}
        <tr>
            <td align="center" style="padding:22px 0 6px;">
                <table cellpadding="0" cellspacing="0" border="0" bgcolor="{{ $order->network->value === 'visa' ? '#1e3a8a' : '#111827' }}" style="width:300px;height:180px;border-radius:16px;background:{{ $order->network->value === 'visa' ? 'linear-gradient(135deg,#1e3a8a,#0f172a)' : 'linear-gradient(135deg,#111827,#3f2d0c)' }};border:1px solid rgba(255,255,255,0.12);">
                    <tr><td style="padding:18px 20px 0;color:#e2e8f0;font-size:13px;font-weight:700;letter-spacing:0.5px;" valign="top">INVORA</td>
                        <td style="padding:18px 20px 0;color:#e2e8f0;font-size:12px;text-align:right;" valign="top">{{ $order->network->label() }}</td></tr>
                    <tr><td colspan="2" style="padding:0 20px;color:#f8fafc;font-size:16px;letter-spacing:2px;font-family:Consolas,Menlo,monospace;" valign="middle">•••• •••• •••• {{ $order->last_four ?: '••••' }}</td></tr>
                    <tr><td style="padding:0 20px 18px;color:#cbd5e1;font-size:11px;letter-spacing:1px;" valign="bottom">{{ $order->name_on_card }}</td>
                        <td style="padding:0 20px 18px;color:#cbd5e1;font-size:11px;text-align:right;" valign="bottom">{{ $order->expiry ?: '' }}</td></tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="padding:20px 24px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid rgba(255,255,255,0.06);">
                    @foreach ($rows as $label => $value)
                        <tr>
                            <td style="padding:11px 0;border-bottom:1px solid rgba(255,255,255,0.06);color:#64748b;font-size:13px;">{{ $label }}</td>
                            <td style="padding:11px 0;border-bottom:1px solid rgba(255,255,255,0.06);color:#e2e8f0;font-size:13px;text-align:right;">{{ $value }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>

        <tr>
            <td align="center" style="padding:4px 24px 30px;">
                <a href="{{ $url }}" style="display:inline-block;background:#6366f1;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;padding:12px 26px;border-radius:10px;">Track your card</a>
            </td>
        </tr>
    </table>
</td>
</tr>
@endsection
