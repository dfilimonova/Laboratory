<?php
session_start();
if (isset($_SESSION['user_id'])) {
header("Location: profile.php");
exit();
}
$host = 'localhost';
$db_name = 'theater';
$username = 'root';
$password = '';

try {
$pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
die("Ошибка подключения к базе данных: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

try {
$check_username_query = "SELECT * FROM users WHERE username=:username";
$stmt = $pdo->prepare($check_username_query);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    die("Имя пользователя уже занято.");
}
$check_email_query = "SELECT * FROM users WHERE email=:email";
$stmt = $pdo->prepare($check_email_query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    die("Адрес почты уже используется.");
}


$insert_user_query = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)";
$stmt = $pdo->prepare($insert_user_query);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->bindParam(':password', $password, PDO::PARAM_STR);
$stmt->execute();


$user_id = $pdo->lastInsertId();


$insert_user_role_query = "INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, 1)";
$stmt = $pdo->prepare($insert_user_role_query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();


header("Location: login.php");
exit();
} catch (PDOException $e) {
die("Ошибка при регистрации: " . $e->getMessage());
}
}

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Регистрация</title>
<link rel="stylesheet" href="/css/registration.css">
</head>
<body>
<?php include "layout.php"?>
<div class="container">
<h2>Регистрация</h2>
<form action="registration.php" method="post" onsubmit="return validateForm()">
<label for="username">Имя пользователя:</label>
<input type="text" id="username" name="username" required>
<p id="usernameError" class="error"></p>

<label for="email">Email:</label>
<input type="email" id="email" name="email" required>

<label for="password">Пароль:</label>
<input type="password" id="password" name="password" required>
<p id="passwordError" class="error"></p>

<button type="submit">Зарегистрироваться</button>
</form>
</div>

<script>
function validateForm() {
var username = document.getElementById('username').value;
var password = document.getElementById('password').value;


var usernameRegex = /^[a-zA-Z0-9_]{3,16}$/;
if (!usernameRegex.test(username)) {
    document.getElementById('usernameError').innerText = 'Имя пользователя должно содержать от 3 до 16 символов: буквы, цифры, знаки подчеркивания';
    return false;
} else {
    document.getElementById('usernameError').innerText = '';
}


var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
if (!passwordRegex.test(password)) {
    document.getElementById('passwordError').innerText = 'Пароль должен содержать минимум 8 символов, хотя бы одну цифру и одну букву';
    return false;
} else {
    document.getElementById('passwordError').innerText = '';
}

return true;
}
</script>
</body>
</html>
