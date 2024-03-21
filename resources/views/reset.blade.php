<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset</title>
</head>
<body>
    <h1>You have Request to reset your password</h1>
    <hr>
    <p>We cannot simply send you your old password.A unique
        link to reset your password.
    </p>
    <h1><a href="http://127.0.0.1:3000/api/user/reset/{{$token}}">Click here to Reset Your password</a></h1>
</body>
</html>