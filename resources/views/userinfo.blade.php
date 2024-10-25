<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="{{ asset('css/style.css') }}">
    <title>Данные пользователя</title>
</head>
<style>
 
</style>
<body>
    <div class="container">
        <h1>Данные пользователя</h1>
        <h1> Почта - {{$user?->email ?? 'Нет данных'}} </h1>
        <h1> Телефон - {{$user?->phone ?? 'Нет данных'}} </h1>
        <h1> Имя - {{ $user?->name ?? 'Нет данных' }} </h1>
      
    <form action="{{ url('api/auth/logout') }}" method="POST">
        <button type="submit" class="btn">Выйти из аккаунта</button>
    </form>
    </div>
    
</body>
</html>
