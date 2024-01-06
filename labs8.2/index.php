<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Календарь Widget</title>
    <style>
        .calendar-widget {
            font-family: Arial, sans-serif;
            border: 1px solid #ccc;
            padding: 10px;
            width: 300px;
            text-align: center;
        }

        table {
            width: 100%;
        }

        th, td {
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            border: 1px solid #ddd;
            text-align: center;
        }

        .highlight {
            background-color: lightgray;
        }

        .weekend {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php
setlocale(LC_TIME, 'ru_RU.UTF-8');

function isWeekend($date) {
    return (strftime('%u', strtotime($date)) >= 6);
}

function isHoliday($date) {
    $holidays = array(
        '01-01', // Новый год
        '07-01', // Рождество
        '08-03', // Праздник весны и труда
        '09-05', // День Победы
        '12-31'  // Новый год (дополнительный день)
    );
    return in_array(date('m-d', strtotime($date)), $holidays);
}

$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('n');

$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
$firstDayOfWeek = date('N', strtotime("$selectedYear-$selectedMonth-01"));

echo "<div class='calendar-widget'>";
echo "<h2>Календарь</h2>";
echo "<form method='get'>";
echo "<select name='month'>";
for ($i = 1; $i <= 12; $i++) {
    $monthName = strftime('%B', mktime(0, 0, 0, $i, 1));
    echo "<option value='$i' " . ($i == $selectedMonth ? "selected" : "") . ">$monthName</option>";
}
echo "</select>";
echo "<select name='year'>";
for ($i = $selectedYear - 10; $i <= $selectedYear + 10; $i++) {
    echo "<option value='$i' " . ($i == $selectedYear ? "selected" : "") . ">$i</option>";
}
echo "</select>";
echo "<input type='submit' value='Показать'>";
echo "</form>";

echo "<h2>" . strftime('%B %Y', strtotime("$selectedYear-$selectedMonth-01")) . "</h2>";
echo "<table>";
echo "<tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Вс</th></tr>";
echo "<tr>";
for ($i = 1; $i < $firstDayOfWeek; $i++) {
    echo "<td></td>";
}
for ($day = 1; $day <= $daysInMonth; $day++) {
    $date = date('Y-m-d', mktime(0, 0, 0, $selectedMonth, $day, $selectedYear));
    $highlight = (isWeekend($date) || isHoliday($date)) ? 'highlight' : '';
    echo "<td class='$highlight'>" . $day . "</td>";
    if (date('N', mktime(0, 0, 0, $selectedMonth, $day + 1, $selectedYear)) == 1) {
        echo "</tr>";
        if ($day != $daysInMonth) {
            echo "<tr>";
        }
    }
}
echo "</table>";
echo "</div>";
?>
</body>
</html>
