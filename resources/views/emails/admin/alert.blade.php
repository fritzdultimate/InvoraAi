@extends('emails.layouts.app')

@section('content')
<tr>
<td align="center" style="padding:20px 10px;background:#020617;">
    <!-- MAIN CONTAINER -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;margin:0 auto;background:#0f172a;border-radius:14px;border:1px solid rgba(255,255,255,0.05);">

        @php
            $palette = [
                'success' => ['bg' => 'rgba(34,197,94,0.12)', 'fg' => '#22c55e'],
                'warning' => ['bg' => 'rgba(245,158,11,0.12)', 'fg' => '#f59e0b'],
                'danger'  => ['bg' => 'rgba(239,68,68,0.12)', 'fg' => '#ef4444'],
                'info'    => ['bg' => 'rgba(56,189,248,0.12)', 'fg' => '#38bdf8'],
            ];
            $c = $palette[$badgeColor] ?? $palette['info'];
        @endphp

        <!-- HEADER -->
        <tr>
            <td style="padding:24px 20px;text-align:center;">
                <span style="display:inline-block;background:{{ $c['bg'] }};color:{{ $c['fg'] }};font-size:11px;padding:6px 12px;border-radius:999px;font-weight:600;letter-spacing:0.6px;">
                    {{ $badge }}
                </span>

                <h1 style="color:#ffffff;font-size:22px;font-weight:700;margin:16px 0 8px;line-height:1.3;">
                    {{ $heading }}
                </h1>

                <p style="color:#94a3b8;font-size:14px;line-height:1.6;margin:0 auto;max-width:460px;">
                    {{ $intro }}
                </p>
            </td>
        </tr>

        <!-- DETAILS -->
        <tr>
            <td align="center" style="padding:0 20px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#020617;border-radius:12px;border:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <td style="padding:16px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                @foreach($rows as $label => $value)
                                    <tr>
                                        <td style="color:#64748b;font-size:12px;padding:6px 0;white-space:nowrap;">{{ $label }}</td>
                                        <td align="right" style="color:#e2e8f0;font-size:12px;font-weight:500;padding-left:16px;">{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- CTA -->
        @if($url)
        <tr>
            <td align="center" style="padding:0 20px 20px;">
                <a href="{{ $url }}" style="background:linear-gradient(135deg,#009A76,#22c55e);padding:12px 26px;border-radius:8px;color:#ffffff;font-weight:600;text-decoration:none;display:inline-block;font-size:14px;">
                    {{ $ctaLabel ?? 'View in Admin' }}
                </a>
            </td>
        </tr>
        @endif

        <!-- FOOTNOTE -->
        <tr>
            <td align="center" style="padding:0 20px 24px;color:#64748b;font-size:12px;line-height:1.5;text-align:center;">
                This is an automated alert from InvoraAI systems.
            </td>
        </tr>

    </table>
</td>
</tr>

<!-- RESPONSIVE MEDIA -->
<style type="text/css">
    @media only screen and (max-width:480px){
        h1{font-size:19px !important;}
        td, p{font-size:13px !important;}
        a{padding:10px 20px !important;}
    }
</style>

@endsection
