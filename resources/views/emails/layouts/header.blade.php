<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Invora Notification</title>
</head>

<body style="margin:0;padding:0;background:#020617;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#020617;padding:40px 0;">
        <tr>
            <td align="center">

                <!-- MAIN CONTAINER -->
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#0f172a;border-radius:14px;border:1px solid rgba(255,255,255,0.05);overflow:hidden;">

                    <!-- HEADER -->
                    <tr>
                        <td style="padding:24px 30px;border-bottom:1px solid rgba(255,255,255,0.05);">

                            <table width="100%">
                                <tr>

                                    <td align="left">
                                        <img src="https://yourdomain.com/logo.png" width="130" style="display:block;">
                                    </td>

                                    <td align="right" style="color:#64748b;font-size:12px;">
                                        Secure Notification
                                    </td>

                                </tr>
                            </table>

                        </td>
                    </tr>
                    @php
                        $title = 'Some title';
                        $message = 'This is some messages i have for you bitch';
                        $action_url = 'fff';
                       $action_text = 'View'; 
                    @endphp

                    <!-- CONTENT -->
                    <tr>
                        <td style="padding:30px;">

                            <h2 style="margin:0;color:#ffffff;font-size:20px;">
                                {{ $title }}
                            </h2>

                            <p style="color:#94a3b8;font-size:14px;line-height:1.6;margin-top:14px;">
                                {{ $message }}
                            </p>

                        </td>
                    </tr>


                    <!-- BUTTON -->
                    <tr>
                        <td align="center" style="padding:10px 30px 30px 30px;">

                            <a href="{{ $action_url }}" style="display:inline-block;
                                background:linear-gradient(135deg,#2563eb,#1d4ed8);
                                padding:12px 24px;
                                border-radius:10px;
                                color:#ffffff;
                                text-decoration:none;
                                font-weight:600;
                                font-size:14px;"
                            >

                                {{ $action_text }}

                            </a>

                        </td>
                    </tr>