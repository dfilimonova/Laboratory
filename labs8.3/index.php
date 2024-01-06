<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация пользователя</title>
</head>
<body>

<h2>Регистрация пользователя</h2>

<form action="registration_process.php" method="post">
    <div>
        <label for="full_name">ФИО:</label>
        <input type="text" id="full_name" name="full_name" required>
    </div>
    <div>
        <label for="username">Логин:</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="dob">Дата рождения:</label>
        <input type="date" id="dob" name="dob" required>
    </div>
    <div>
        <input type="submit" value="Зарегистрироваться">
    </div>
</form>
<?php
// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $full_name = $_POST["full_name"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $dob = $_POST["dob"];
}?>
</body>
</html>
