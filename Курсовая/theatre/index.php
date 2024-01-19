<?php
session_start();

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

function fetchPerformancesWithSessions($pdo)
{
$performances = $pdo->query("SELECT * FROM performances")->fetchAll(PDO::FETCH_ASSOC);

foreach ($performances as &$performance) {
$performance['sessions'] = fetchSessionsForPerformance($pdo, $performance['id']);
}

return $performances;
}

function fetchSessionsForPerformance($pdo, $performanceId)
{
$sessions = $pdo->prepare("SELECT * FROM sessions WHERE performance_id = :performance_id");
$sessions->bindParam(':performance_id', $performanceId, PDO::PARAM_INT);
$sessions->execute();

return $sessions->fetchAll(PDO::FETCH_ASSOC);
}

function isUserLoggedIn()
{
return isset($_SESSION['user_id']);
}
function isSessionBooked($pdo, $userId, $sessionId)
{
$check_booking_query = "SELECT * FROM bookings WHERE user_id = :user_id AND session_id = :session_id";
$stmt = $pdo->prepare($check_booking_query);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->bindParam(':session_id', $sessionId, PDO::PARAM_INT);
$stmt->execute();

return $stmt->rowCount() > 0;
}
function bookSession($pdo, $userId, $sessionId)
{
$insert_booking_query = "INSERT INTO bookings (user_id, session_id) VALUES (:user_id, :session_id)";
$stmt = $pdo->prepare($insert_booking_query);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->bindParam(':session_id', $sessionId, PDO::PARAM_INT);
$stmt->execute();
}

function cancelBooking($pdo, $userId, $sessionId)
{
$delete_booking_query = "DELETE FROM bookings WHERE user_id = :user_id AND session_id = :session_id";
$stmt = $pdo->prepare($delete_booking_query);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->bindParam(':session_id', $sessionId, PDO::PARAM_INT);
$stmt->execute();
}
$performancesWithSessions = fetchPerformancesWithSessions($pdo);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_session']) && isUserLoggedIn()) {
$sessionIdToBook = $_POST['book_session'];
bookSession($pdo, $_SESSION['user_id'], $sessionIdToBook);
header("Location: index.php");
exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_booking']) && isUserLoggedIn()) {
$sessionIdToCancel = $_POST['cancel_booking'];
cancelBooking($pdo, $_SESSION['user_id'], $sessionIdToCancel);
header("Location: index.php");
exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Главная страница</title>
<?php include "views/layout.php";?>
<style>
body {
font-family: 'Arial', sans-serif;
background-color: #f7f7f7;
margin: 0;
padding: 0;
}

.performance-section {
margin-top: 20px;
cursor: pointer;
border: 1px solid #ccc;
border-radius: 8px;
padding: 10px;
background-color: #fff;
}

.performance-section h2 {
display: flex;
justify-content: space-between;
align-items: center;
}

.performance-content {
display: none;
margin-top: 10px;
max-height: 0;
overflow: hidden;
transition: max-height 0.3s ease-out;
}

.performance-content.active {
display: block;
max-height: 1000px; /* Выберите достаточно большое значение */
}

.arrow {
display: inline-block;
width: 0;
height: 0;
border-left: 5px solid transparent;
border-right: 5px solid transparent;
border-bottom: 5px solid #333;
transition: transform 0.3s ease-out;
}

.arrow.down {
transform: rotate(180deg);
}

.session-section {
margin-top: 10px;
}

.booking-button {
background-color:#8e44ad;;
color: white;
padding: 10px;
text-align: center;
text-decoration: none;
display: inline-block;
font-size: 16px;
cursor: pointer;
border-radius: 5px;
}

.booking-button:hover {
background-color: #6c3483;
}

body::before {
content: "";
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background: url('/img/theater.jpg') no-repeat center center fixed;
background-size: cover;
z-index: -1;
}
h1 {
text-align: center;
color: #333;
}
.performance-content a{
color #333333;
}
</style>
</head>
<body>



<?php foreach ($performancesWithSessions as $performance) : ?>
<div class="performance-section" onclick="toggleContent('performance<?= $performance['id'] ?>')">
<h2><?= $performance['title'] ?> <span class="arrow" id="arrowPerformance<?= $performance['id'] ?>"></span></h2>
<div class="performance-content" id="performance<?= $performance['id'] ?>">
<?php foreach ($performance['sessions'] as $session) : ?>
<div class="session-section">
<?= $session['date_time'] ?>
<a href="/views/session_details.php?session_id=<?php echo $session['id']; ?>">Подробнее</a>
<?php if (isUserLoggedIn()) : ?>
    <?php if (isSessionBooked($pdo, $_SESSION['user_id'], $session['id'])) : ?>
        <form action="index.php" method="post" style="display: inline;">
            <input type="hidden" name="cancel_booking" value="<?= $session['id'] ?>">
            <button type="submit" class="booking-button">Отменить</button>
        </form>
    <?php else : ?>
        <form action="index.php" method="post" style="display: inline;">
            <input type="hidden" name="book_session" value="<?= $session['id'] ?>">
            <button type="submit" class="booking-button">Забронировать</button>
        </form>
    <?php endif; ?>
<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
</div>
<?php endforeach; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
document.querySelectorAll('.performance-section').forEach(function (section) {
section.addEventListener('click', function (event) {
    if (event.target.classList.contains('arrow')) {
        event.stopPropagation();
        var sectionId = this.id;
        toggleContent(sectionId);
    }
});

section.querySelectorAll('.arrow, form, input, button').forEach(function (element) {
    element.addEventListener('click', function (event) {
        event.stopPropagation();
    });
});
});
});

function toggleContent(sectionId) {
var content = document.getElementById(sectionId);
var arrow = document.getElementById('arrow' + sectionId.charAt(0).toUpperCase() + sectionId.slice(1));

content.classList.toggle('active');
arrow.classList.toggle('down');
}
</script>
</body>
</html>
