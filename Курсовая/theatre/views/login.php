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
$password = $_POST['password'];

try {
    $find_user_query = "SELECT * FROM users WHERE username=:username";
    $stmt = $pdo->prepare($find_user_query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: profile.php");
            exit();
        } else {
            $error_message = "Неверное имя пользователя или пароль.";
        }
    } else {
        $error_message = "Неверное имя пользователя или пароль.";
    }
} catch (PDOException $e) {
    die("Ошибка при авторизации: " . $e->getMessage());
}
}

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Авторизация</title>
<link rel="stylesheet" href="/css/login.css">
</head>
<body>
<?php include "layout.php";?>
<div class="container">
<h2>Авторизация</h2>
<?php if (isset($error_message)) : ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>
<form action="login.php" method="post">
    <label for="username">Имя пользователя:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Войти</button>
</form>
</div>
</body>
</html>
