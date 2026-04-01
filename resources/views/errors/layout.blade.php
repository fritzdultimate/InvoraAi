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
        }

        .invora-error-card {
            max-width: 520px;
            width: 90%;
            padding: 32px;
            border-radius: 18px;

            background: linear-gradient(145deg, rgba(20,25,40,0.95), rgba(10,15,30,0.98));
            border: 1px solid rgba(255,255,255,0.06);

            box-shadow: 0 30px 80px rgba(0,0,0,0.7);
            text-align: center;
        }

        .invora-code {
            font-size: 72px;
            font-weight: 800;
            background: linear-gradient(135deg, #22c55e, #009A76);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .invora-title {
            font-size: 22px;
            margin-top: 10px;
            font-weight: 600;
        }

        .invora-text {
            font-size: 14px;
            color: #94a3b8;
            margin-top: 10px;
            line-height: 1.6;
        }

        .invora-actions {
            margin-top: 24px;
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
    </style>
</head>
<body>

<div class="invora-error-card">

    <div class="invora-code">
        {{ $code ?? 'Error' }}
    </div>

    <div class="invora-title">
        {{ $title ?? 'Something went wrong' }}
    </div>

    <div class="invora-text">
        {{ $message ?? 'An unexpected issue occurred. Please try again or return to dashboard.' }}
    </div>

    <div class="invora-actions">
        <a href="{{ url('/') }}" class="invora-btn primary">Go Home</a>
        <a href="{{ url()->previous() }}" class="invora-btn secondary">Go Back</a>
    </div>

</div>

</body>
</html>