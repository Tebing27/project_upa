<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $appName }}</title>
    </head>
    <body style="margin:0; padding:0; background-color:#f4f7f1; color:#163020; font-family:Arial, Helvetica, sans-serif;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:24px 12px;">
            <tr>
                <td align="center">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:18px; overflow:hidden; border:1px solid #d9e4d3;">
                        <tr>
                            <td style="padding:32px 32px 12px; background:linear-gradient(135deg, #163020 0%, #2f5b2a 100%); color:#ffffff;">
                                <p style="margin:0; font-size:13px; letter-spacing:0.08em; text-transform:uppercase; opacity:0.8;">
                                    {{ $appName }}
                                </p>
                                <h1 style="margin:12px 0 0; font-size:28px; line-height:1.2;">
                                    Verifikasi Email Anda
                                </h1>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:32px;">
                                <p style="margin:0 0 16px; font-size:16px; line-height:1.7;">
                                    Terima kasih sudah membuat akun. Klik tombol di bawah ini untuk memverifikasi alamat email Anda dan melanjutkan proses pendaftaran.
                                </p>
                                <p style="margin:0 0 28px;">
                                    <a href="{!! $verificationUrl !!}" style="display:inline-block; padding:14px 24px; border-radius:999px; background-color:#2f5b2a; color:#ffffff; font-size:15px; font-weight:700; text-decoration:none;">
                                        Verifikasi Email
                                    </a>
                                </p>
                                <p style="margin:0 0 12px; font-size:14px; line-height:1.7; color:#4d5f4a;">
                                    Jika tombol tidak bekerja, salin tautan berikut ke browser Anda:
                                </p>
                                <p style="margin:0; font-size:13px; line-height:1.7; word-break:break-word; color:#2f5b2a;">
                                    <a href="{!! $verificationUrl !!}" style="color:#2f5b2a;">{!! $verificationUrl !!}</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0 32px 32px; font-size:13px; line-height:1.7; color:#6b7b68;">
                                Jika Anda tidak merasa membuat akun, abaikan email ini.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
