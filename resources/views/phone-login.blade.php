<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация по телефону</title>
    <link rel="stylesheet"  href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="container">
        <h1>Авторизация по телефону</h1>

        <form action="api/auth/phone/callback" method="POST">
            <div class="form-group">
                <label for="phone">Номер телефона</label>
                <input type="text" id="phone" name="phone" class="form-control" required>
            </div>
            <button type="button" id="sendCodeButton" class="btn btn-secondary">Отправить SMS-код</button>
            <div class="form-group hidden" id="smsCodeGroup">
                <label for="sms_code">Введите SMS-код</label>
                <input type="text" id="sms_code" name="sms_code" class="form-control">
            </div>
            <button type="submit" id="phoneLoginButton" class="btn btn-primary hidden">Войти</button>
        </form>
    </div>

    <script>
        document.getElementById('sendCodeButton').addEventListener('click', function () {
            const phone = document.getElementById('phone').value;

            fetch('/api/auth/send-sms-code', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ phone: phone })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    document.getElementById('smsCodeGroup').classList.remove('hidden');
                    document.getElementById('phoneLoginButton').classList.remove('hidden');
                } else {
                    alert('Пользователь с данным номером телефона не найден.');
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });
        });
    </script>
</body>
</html>
