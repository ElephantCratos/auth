<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <h1>Авторизация</h1>
        <form action="api/auth/login" method="POST" id="loginForm">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Войти</button>
        </form>

        <h2 style="margin: 20px 0;">ИЛИ</h2> 

        <div style="margin-bottom: 15px;">
            <script async src="https://telegram.org/js/telegram-widget.js?19" data-telegram-login="AuthRecifraBot"
                data-size="large" data-auth-url="{{ route('loginWithTelegram') }}" data-request-access="write"></script>
        </div>

        <div>
            <a href="{{route('phone-login')}}" class="btn " style="width: 100%;">Войти по номеру телефона</a> 
        </div>
    </div>
</body>
</html>
