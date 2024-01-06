<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Таблица умножения</title>
    <style>
        table {
            border-collapse: collapse;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Таблица умножения</h2>

<table>
    <tr>
        <th>&times;</th>
        <?php
        for($i = 0; $i <= 10; $i++) {
            echo "<th>$i</th>";
        }
        ?>
    </tr>
    <?php
    for($i = 0; $i <= 10; $i++) {
        echo "<tr>";
        echo "<th>$i</th>";
        for($j = 0; $j <= 10; $j++) {
            echo "<td>" . ($i * $j) . "</td>";
        }
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>
