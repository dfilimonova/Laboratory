<!DOCTYPE html>
<html>
<head>
    <title>Регистрация</title>
</head>
<body>
    <h2>Форма регистрации</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      
            <label for="fio">ФИО:</label>
            <input type="text" id="fio" name="fio" required><br><br>
      
            <label for="login">Логин:</label>
            <input type="text" id="login" name="login" required><br><br>
        
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required><br><br>
        
            <label for="dob">Дата рождения:</label>
            <input type="date" id="dob" name="dob" required><br><br>
       
            <input type="submit" value="Зарегистрироваться">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fio = $_POST['fio'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $dob = $_POST['dob'];

        echo "<h2>Данные пользователя:</h2>";
        echo "<p>ФИО: $fio</p>";
        echo "<p>Логин: $login</p>";
        echo "<p>Пароль: $password</p>";
        echo "<p>Дата рождения: $dob</p>";
    }
    ?>
</body>
</html>