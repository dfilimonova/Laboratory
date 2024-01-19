<?php
session_start();
try {
$pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
die("Ошибка подключения к базе данных: " . $e->getMessage());
}
if (!isset($_SESSION['user_id'])) {

header("Location: login.php");
exit();
}
$user_id = $_SESSION['user_id'];
$user_query = "SELECT username, email FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($user_query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user_data) {
$username = $user_data['username'];
$user_email = $user_data['email'];
} else {
header("Location: login.php");
exit();
}
function fetchUserBookings($pdo, $userId)
{
$query = "SELECT bookings.*, sessions.date_time, performances.title as performance_title
    FROM bookings
    JOIN sessions ON bookings.session_id = sessions.id
    JOIN performances ON sessions.performance_id = performances.id
    WHERE bookings.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function cancelBooking($pdo, $bookingId)
{
$query = "DELETE FROM bookings WHERE id = :booking_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
$stmt->execute();
}
$userBookings = fetchUserBookings($pdo, $_SESSION['user_id']);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_booking'])) {
$bookingIdToCancel = $_POST['cancel_booking'];
cancelBooking($pdo, $bookingIdToCancel);
header("Location: profile.php");
exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_POST['new_username'])) {
$new_username = $_POST['new_username'];
$check_username_query = "SELECT COUNT(*) FROM users WHERE username = :new_username AND id != :user_id";
$stmt = $pdo->prepare($check_username_query);
$stmt->bindParam(':new_username', $new_username, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->fetchColumn() == 0) {
$update_username_query = "UPDATE users SET username = :new_username WHERE id = :user_id";
$stmt = $pdo->prepare($update_username_query);
$stmt->bindParam(':new_username', $new_username, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$_SESSION['username'] = $new_username;
header("Location: profile.php");
exit();
} else {
die("Имя уже занято");
}
}
if (isset($_POST['new_email'])) {
$new_email = $_POST['new_email'];
$check_email_query = "SELECT COUNT(*) FROM users WHERE email = :new_email AND id != :user_id";
$stmt = $pdo->prepare($check_email_query);
$stmt->bindParam(':new_email', $new_email, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->fetchColumn() == 0) {
$update_email_query = "UPDATE users SET email = :new_email WHERE id = :user_id";
$stmt = $pdo->prepare($update_email_query);
$stmt->bindParam(':new_email', $new_email, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$_SESSION['user_email'] = $new_email;
header("Location: profile.php");
exit();
} else {

die("Адрес почты уже занят");
}
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Личный кабинет</title>
<link rel="stylesheet" href="/css/profile.css">
</head>
<body>
<?php include "layout.php";?>
<div class="container">
<h2>Личный кабинет</h2>
<p>Добро пожаловать, <?php echo $username; ?>!</p>
<p>Имя пользователя: <?php echo $username; ?>
<button onclick="toggleForm('changeUsernameForm')">Изменить</button>
</p>
<form id="changeUsernameForm" action="profile.php" method="post" style="display: none;">
<label for="new_username">Новое имя пользователя:</label>
<input type="text" id="new_username" name="new_username" required>
<button type="submit">Сохранить изменения</button>
</form>
<p>Email: <?php echo $user_email; ?>
<button onclick="toggleForm('changeEmailForm')">Изменить</button>
</p>
<!-- Форма для изменения адреса электронной почты -->
<form id="changeEmailForm" action="profile.php" method="post" style="display: none;">
<label for="new_email">Новый Email:</label>
<input type="email" id="new_email" name="new_email" required>

<button type="submit">Сохранить изменения</button>
</form>
<h2>Ваши бронирования</h2>
<?php foreach ($userBookings as $booking) : ?>
<div class="booking-section">
<p><?= $booking['performance_title'] ?> - <?= $booking['date_time'] ?></p>
<form action="profile.php" method="post" style="display: inline;">
    <input type="hidden" name="cancel_booking" value="<?= $booking['id'] ?>">
    <button type="submit">Отменить бронь</button>
</form>
</div>
<?php endforeach; ?>
<a href="/methods/logout.php">Выйти</a>
</div>
<script>
function toggleForm(formId) {
var form = document.getElementById(formId);
form.style.display = (form.style.display === 'none') ? 'block' : 'none';
}
</script>
</body>
</html>
