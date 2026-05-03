<?php
session_start();
require_once "dbcon.php";

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

/* 월별 일정 */
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

/* 오늘 일정 */
$today = date("Y-m-d");

$todaySql = "SELECT * FROM tb_todolist 
             WHERE due_date = ?
             ORDER BY status ASC, idx DESC";

$todayStmt = $conn->prepare($todaySql);
$todayStmt->bind_param("s", $today);
$todayStmt->execute();
$todayResult = $todayStmt->get_result();

/* 주간 일정 */
$weekStart = date("Y-m-d", strtotime("monday this week"));
$weekEnd = date("Y-m-d", strtotime("sunday this week"));

$weekSql = "SELECT * FROM tb_todolist 
            WHERE due_date BETWEEN ? AND ?
            ORDER BY due_date ASC, status ASC";

$weekStmt = $conn->prepare($weekSql);
$weekStmt->bind_param("ss", $weekStart, $weekEnd);
$weekStmt->execute();
$weekResult = $weekStmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>ToDoList 달력</title>
  <link rel="stylesheet" href="css/calendar.css">
  <link rel="stylesheet" href="css/seasonEffect.css">
  <script src="js/seasonEffect.js" defer></script>
</head>

<script>
  const calendarMonth = <?= $month ?>;
</script>

<body>

<?php include "header.php"; ?>

<main class="calendar-main">

  <!-- 왼쪽: 오늘의 일정 -->
  <aside class="side-box">
    <h3>오늘의 일정</h3>

    <?php if ($todayResult->num_rows > 0) { ?>
      <?php while ($row = $todayResult->fetch_assoc()) {
        $checked = $row['status'] == 1 ? "checked" : "";
        $doneClass = $row['status'] == 1 ? "done" : "";
      ?>
        <div class="side-todo <?= $doneClass ?>">
          <input type="checkbox" <?= $checked ?> disabled>
          <span><?= htmlspecialchars($row['title']) ?></span>
        </div>
      <?php } ?>
    <?php } else { ?>
      <p class="empty">오늘 일정이 없습니다.</p>
    <?php } ?>
  </aside>


  <!-- 가운데: 월별 달력 -->
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

          $dateParam = sprintf("%04d-%02d-%02d", $year, $month, $day);

          echo "<td class='calendar-cell' onclick=\"openTodoModal('$dateParam')\">";
          echo "<div class='date $class'>$day</div>";

          if (isset($todos[$day])) {
            foreach ($todos[$day] as $todo) {
              $doneClass = $todo['status'] == 1 ? "done" : "";
              $checked = $todo['status'] == 1 ? "checked" : "";

              echo "<div class='todo-item $doneClass'>";
              echo "<div class='todo-title'>";
              echo "<input type='checkbox' $checked onclick='event.stopPropagation()' disabled>";
              echo "<span>" . htmlspecialchars($todo['title']) . "</span>";
              echo "</div>";
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


  <!-- 오른쪽: 주간 일정표 -->
  <aside class="side-box">
    <h3>주간 일정표</h3>

    <?php if ($weekResult->num_rows > 0) { ?>
      <?php while ($row = $weekResult->fetch_assoc()) {
        $checked = $row['status'] == 1 ? "checked" : "";
        $doneClass = $row['status'] == 1 ? "done" : "";
        $dateText = date("m/d", strtotime($row['due_date']));
      ?>
        <div class="side-todo <?= $doneClass ?>">
          <span class="side-date"><?= $dateText ?></span>
          <input type="checkbox" <?= $checked ?> disabled>
          <span><?= htmlspecialchars($row['title']) ?></span>
        </div>
      <?php } ?>
    <?php } else { ?>
      <p class="empty">이번 주 일정이 없습니다.</p>
    <?php } ?>
  </aside>

</main>


<!-- 모달 / 모바일 바텀시트 -->
<div class="modal-wrap" id="todoModal" onclick="closeTodoModal()">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-header">
      <h3 id="modalDateTitle">일정</h3>
      <button type="button" class="modal-close" onclick="closeTodoModal()">×</button>
    </div>

    <div id="modalContent" class="modal-content">
      불러오는 중...
    </div>
  </div>
</div>

<script>
function openTodoModal(date) {
  const modal = document.getElementById("todoModal");
  const title = document.getElementById("modalDateTitle");
  const content = document.getElementById("modalContent");

  title.innerText = date + " 일정";
  content.innerHTML = "불러오는 중...";
  modal.style.display = "block";

  fetch("todo_modal_data.php?date=" + encodeURIComponent(date))
    .then(function(response) {
      return response.text();
    })
    .then(function(data) {
      content.innerHTML = data;
    })
    .catch(function() {
      content.innerHTML = "<p class='empty'>일정을 불러오지 못했습니다.</p>";
    });
}

function closeTodoModal() {
  document.getElementById("todoModal").style.display = "none";
}
</script>

</body>
</html>

<?php
$stmt->close();
$todayStmt->close();
$weekStmt->close();
$conn->close();
?>