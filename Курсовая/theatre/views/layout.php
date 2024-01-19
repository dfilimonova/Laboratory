<?php
session_start();


$host = 'localhost';
$db_name = 'theater';
$name = 'root';
$password = '';

try {
$pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $name, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
die("Ошибка подключения к базе данных: " . $e->getMessage());
}


function getUserRole($pdo, $userId)
{
$query = "SELECT roles.name
    FROM user_roles
    JOIN roles ON user_roles.role_id = roles.id
    WHERE user_roles.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

return $result['name'] ?? null;
}

if (isset($_SESSION['user_id'])) {
$userRole = getUserRole($pdo, $_SESSION['user_id']);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Название вашего сайта</title>

</head>

<body>
<style>


body {
font-family: Arial, sans-serif;
margin: 0;
padding: 0;
}

nav {
text-align: center;
background-color: #333;
padding: 10px;
}

ul {
list-style-type: none;
margin: 0;
padding: 0;
}

li {
display: inline;
margin-right: 20px;
}

nav a {
text-decoration: none;
color: #fff;
font-weight: bold;
}

a:hover {
color: #ffcc00;
}

</style>
<nav>
<ul>
<li><a href="/index.php">Главная</a></li>

<?php if (isset($_SESSION['user_id'])): ?>
<?php if ($userRole == 'admin'): ?>
    <li><a href="/views/admin_panel.php">Админка</a></li>
<?php endif; ?>
<li><a href="/views/profile.php">Личный кабинет</a></li>
<li><a href="/methods/logout.php">Выход</a></li>
<?php else: ?>
<li><a href="/views/login.php">Вход</a></li>
<li><a href="/views/registration.php">Регистрация</a></li>
<?php endif; ?>
</ul>
</nav>
</body>
</html>