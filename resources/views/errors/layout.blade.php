<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Something went wrong' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            font-family: Inter, sans-serif;
            background: radial-gradient(circle at top, #0f172a, #020617);
            color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }

        /* subtle glow background */
        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(34,197,94,0.15), transparent 70%);
            filter: blur(80px);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .invora-error-card {
            position: relative;
            max-width: 520px;
            width: 90%;
            padding: 36px;
            border-radius: 18px;

            background: linear-gradient(145deg, rgba(20,25,40,0.95), rgba(10,15,30,0.98));
            border: 1px solid rgba(255,255,255,0.06);

            box-shadow: 0 30px 80px rgba(0,0,0,0.7);
            text-align: center;

            backdrop-filter: blur(10px);
        }

        .invora-icon {
            font-size: 42px;
            margin-bottom: 12px;
        }

        .invora-code {
            font-size: 68px;
            font-weight: 900;
            letter-spacing: -2px;
            background: linear-gradient(135deg, #22c55e, #009A76);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .invora-title {
            font-size: 22px;
            margin-top: 6px;
            font-weight: 600;
        }

        .invora-text {
            font-size: 14px;
            color: #94a3b8;
            margin-top: 12px;
            line-height: 1.6;
        }

        .invora-hint {
            margin-top: 14px;
            font-size: 12px;
            color: #64748b;
        }

        .invora-actions {
            margin-top: 26px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .invora-btn {
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }

        .primary {
            background: linear-gradient(135deg,#009A76,#22c55e);
            color: #fff;
        }

        .secondary {
            border: 1px solid rgba(255,255,255,0.1);
            color: #cbd5f5;
        }

        .invora-btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .invora-footer {
            margin-top: 18px;
            font-size: 11px;
            color: #475569;
        }
    </style>
</head>
<body>

@php
    $icon = match($code ?? 500) {
        404 => '🔍',
        403 => '🚫',
        419 => '⏳',
        429 => '⚡',
        503 => '🛠️',
        default => '💥'
    };

    $hint = match($code ?? 500) {
        404 => 'The link may be broken or removed.',
        403 => 'Your account does not have permission.',
        419 => 'Session expired due to inactivity.',
        429 => 'You’re sending requests too quickly.',
        503 => 'System maintenance in progress.',
        default => 'Unexpected system failure.'
    };
@endphp

<div class="invora-error-card">

    <div class="invora-icon">{{ $icon }}</div>

    <div class="invora-code">
        {{ $code ?? 'Error' }}
    </div>

    <div class="invora-title">
        {{ $title ?? 'Something went wrong' }}
    </div>

    <div class="invora-text">
        {{ $message ?? 'We hit a small issue. Don’t worry, it’s not your fault.' }}
    </div>

    <div class="invora-hint">
        {{ $hint }}
    </div>

    <div class="invora-actions">
        <a href="{{ url('/') }}" class="invora-btn primary">Go Home</a>
        <a href="{{ url()->previous() }}" class="invora-btn secondary">Go Back</a>
    </div>

    <!-- <div class="invora-footer">
        Redirecting automatically in 8 seconds...
    </div> -->

</div>

<script>
    setTimeout(() => {
        // window.location.href = "{{ url('/') }}";
    }, 8000);
</script>

</body>
</html>