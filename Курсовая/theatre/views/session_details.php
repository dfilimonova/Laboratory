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

$session_id = $_GET['session_id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
$user_id = $_SESSION['user_id'];
$performance_id = $_POST['performance_id'];
$review = $_POST['review'];

// Вставка отзыва в базу данных
$insert_review_query = "INSERT INTO reviews (user_id, performance_id, review) VALUES (:user_id, :performance_id, :review)";
$stmt_review = $pdo->prepare($insert_review_query);
$stmt_review->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_review->bindParam(':performance_id', $performance_id, PDO::PARAM_INT);
$stmt_review->bindParam(':review', $review, PDO::PARAM_STR);
$stmt_review->execute();

// Перенаправление на страницу сеанса
header("Location: session_details.php?session_id=$session_id");
exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['session_id'])) {
$session_id = $_GET['session_id'];

// Получение информации о сеансе
$session_query = "SELECT sessions.*, performances.title as performance_title, halls.name as hall_name 
            FROM sessions 
            JOIN performances ON sessions.performance_id = performances.id
            JOIN halls ON sessions.hall_id = halls.id
            WHERE sessions.id = :session_id";
$stmt_session = $pdo->prepare($session_query);
$stmt_session->bindParam(':session_id', $session_id, PDO::PARAM_INT);
$stmt_session->execute();
$session = $stmt_session->fetch(PDO::FETCH_ASSOC);

// Получение отзывов о сеансе
$reviews_query = "SELECT reviews.*, users.username 
            FROM reviews 
            JOIN users ON reviews.user_id = users.id
            WHERE reviews.performance_id = :performance_id";
$stmt_reviews = $pdo->prepare($reviews_query);
$stmt_reviews->bindParam(':performance_id', $session['performance_id'], PDO::PARAM_INT);
$stmt_reviews->execute();
$reviews = $stmt_reviews->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Подробная информация о сеансе</title>
<link rel="stylesheet" href="styles.css">

</head>
<body>
<?php include "layout.php"; ?>
<style>
body {
font-family: Arial, sans-serif;
margin: 0;
padding: 0;
background-color: #f4f4f4;
}

.container {
max-width: 800px;
margin: 20px auto;
background-color: #fff;
padding: 20px;
border-radius: 8px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1, h2, h3 {
color: #333;
}

ul {
list-style-type: none;
padding: 0;
}

li {
margin-bottom: 10px;
}

form {
margin-top: 20px;
}

label {
display: block;
margin-bottom: 8px;
}

textarea {
width: 100%;
padding: 8px;
margin-bottom: 10px;
}

button {
background-color: #8e44ad;
color: #fff;
padding: 10px;
border: none;
border-radius: 4px;
cursor: pointer;
}

button:hover {
background-color: #6c3483;
}

h3 {
margin-top: 20px;
}



.reviews-wrapper {
margin-top: 20px;
}

.review {
background-color: #f9f9f9;
padding: 10px;
border-radius: 8px;
border: 1px solid #ddd;
margin-bottom: 15px;
}

.review .username {
font-weight: bold;
color: #333;
}

.review .content {
margin-top: 8px;
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
</style>
<div class="container">
<h1>Подробная информация о сеансе</h1>

<?php if (isset($session)) : ?>
<h2><?php echo $session['performance_title'] . ' - ' . $session['hall_name'] . ' - ' . $session['date_time']; ?></h2>

<?php if (isset($_SESSION['user_id'])) : ?>
<form action="session_details.php?session_id=<?php echo $session_id; ?>" method="post">
    <input type="hidden" name="performance_id" value="<?php echo $session['performance_id']; ?>">
    <label for="review">Оставьте отзыв:</label>
    <textarea id="review" name="review" rows="4" required></textarea>
    <button type="submit" name="submit_review">Отправить отзыв</button>
</form>
<?php endif; ?>

<h3>Отзывы:</h3>
<div class="reviews-wrapper">
<?php foreach ($reviews as $review) : ?>
    <div class="review">
        <span class="username"><?php echo $review['username']; ?>:</span>
        <div class="content"><?php echo $review['review']; ?></div>
    </div>
<?php endforeach; ?>
</div>
<?php else : ?>
<p>Сеанс не найден.</p>
<?php endif; ?>
</div>
</body>
</html>

