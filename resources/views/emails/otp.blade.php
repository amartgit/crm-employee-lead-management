<!-- resources/views/emails/otp_verification.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
</head>
<body>
  <p><strong>OTP for Login:</strong></p>
<p>Employee ID: {{ $user->employee_id }}</p>
<p>Name: {{ $user->name }}</p>
<p>OTP: <strong style="font-size: 20px;">{{ $otp }}</strong></p>

</body>
</html>
