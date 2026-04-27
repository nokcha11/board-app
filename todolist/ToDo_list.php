<?php
$host = "localhost";
$dbname = "lie8220";
$user = "lie8220";
$password = "koko8220#";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');

if ($month < 1) {
  $month = 12;
  $year--;
}

if ($month > 12) {
  $month = 1;
  $year++;
}

$firstDay = "$year-" . sprintf("%02d", $month) . "-01";
$startWeek = date('w', strtotime($firstDay));
$lastDate = date('t', strtotime($firstDay));

$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) {
  $prevMonth = 12;
  $prevYear--;
}

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) {
  $nextMonth = 1;
  $nextYear++;
}

$sql = "SELECT * FROM tb_todolist 
        WHERE YEAR(due_date) = ? AND MONTH(due_date) = ?
        ORDER BY due_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $year, $month);
$stmt->execute();
$result = $stmt->get_result();

$todos = [];

while ($row = $result->fetch_assoc()) {
  $day = intval(date('j', strtotime($row['due_date'])));
  $todos[$day][] = $row;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>ToDoList 달력</title>
  <link rel="stylesheet" href="css/calendar.css">
</head>
<body>

<div class="calendar-wrap">

  <div class="calendar-header">
    <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>">◀</a>
    <h2><?= $year ?>.<?= sprintf("%02d", $month) ?></h2>
    <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>">▶</a>
  </div>

  <div class="top-btn">
    <a href="ToDo_insert.html">+ ToDo 추가</a>
  </div>

  <table class="calendar">
    <thead>
      <tr>
        <th class="sun">일</th>
        <th>월</th>
        <th>화</th>
        <th>수</th>
        <th>목</th>
        <th>금</th>
        <th class="sat">토</th>
      </tr>
    </thead>

    <tbody>
      <tr>
      <?php
      for ($i = 0; $i < $startWeek; $i++) {
        echo "<td></td>";
      }

      for ($day = 1; $day <= $lastDate; $day++) {
        $week = date('w', strtotime("$year-$month-$day"));
        $class = "";

        if ($week == 0) $class = "sun";
        if ($week == 6) $class = "sat";

        echo "<td>";
        echo "<div class='date $class'>$day</div>";

        if (isset($todos[$day])) {
          foreach ($todos[$day] as $todo) {
          $doneClass = $todo['status'] == 1 ? "done" : "";
          $checked = $todo['status'] == 1 ? "checked" : "";

          echo "<div class='todo-item $doneClass'>";
          echo "CHECK_TEST ";
          echo "<input type='checkbox' $checked disabled style='width:14px;height:14px;display:inline-block;'>";
          echo "<span>" . htmlspecialchars($todo['title']) . "</span>";
          echo "</div>";
          }
        }

        echo "</td>";

        if ($week == 6 && $day != $lastDate) {
          echo "</tr><tr>";
        }
      }

      $lastWeek = date('w', strtotime("$year-$month-$lastDate"));
      for ($i = $lastWeek; $i < 6; $i++) {
        echo "<td></td>";
      }
      ?>
      </tr>
    </tbody>
  </table>

</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>