<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'InvoraAI' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-dark flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-white">InvoraAI</h1>
            <p class="text-muted text-sm">AI Arbitrage Investment Platform</p>
        </div>

        <div class="bg-slate border border-border rounded-xl p-6 shadow-lg">
            {{ $slot }}
        </div>

        <p class="text-xs text-muted text-center mt-6">
            🔒 Protected by enterprise-grade security
        </p>
    </div>

    @livewireScripts
</body>
</html>
