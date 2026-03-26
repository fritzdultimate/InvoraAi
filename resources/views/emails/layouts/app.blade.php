<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>InvoraAI</title>
</head>

<body
    style="margin:0;padding:0;background:#020617;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Arial,sans-serif;">

    <!-- FULL WRAPPER -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#020617;padding:40px 10px;">
        <tr>
            <td align="center">

                <!-- CONTAINER -->
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:640px;background:#0b1220;border-radius:16px;overflow:hidden;border:1px solid rgba(255,255,255,0.06);box-shadow:0 10px 40px rgba(0,0,0,0.6);">

                    @include('emails.layouts.header')

                    @yield('content')

                    @include('emails.layouts.footer')

                </table>

            </td>
        </tr>
    </table>

</body>

</html>