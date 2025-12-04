<!DOCTYPE html>
<html>
<head>
    <title>{{ $emailData['subject'] }}</title>
</head>
<body>
<h1>{{ $emailData['subject'] }}</h1>
<p>Dear {{ $emailData['to_name'] }},</p>
<p>Please find attached the Daily Bullion Reports for {{ date('d/m/Y') }}.</p>
<p>Best regards,</p>
<img src="{{ env('MAIL_LOGO_PATH', 'https://yugjewellery.in/media/photos/logo-base.png') }}" alt="Logo" width="150" />
</body>
</html>


