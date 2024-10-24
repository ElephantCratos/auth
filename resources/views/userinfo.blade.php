<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Данные пользователя</title>
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
}

.user-card {
    margin: 20px 0;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f1f1f1;
}

.btn {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 15px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
}

.btn:hover {
    background-color: #0056b3;
}
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
