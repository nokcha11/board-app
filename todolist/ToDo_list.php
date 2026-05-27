<?php
date_default_timezone_set('Asia/Seoul');
session_start();
require_once "dbcon.php";

if (!isset($_SESSION['idx'])) {
  echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
  exit;
}

$memberIdx = (int)$_SESSION['idx'];
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$columnResult = $conn->query("SHOW COLUMNS FROM tb_todolist LIKE 'member_idx'");
if (!$columnResult || $columnResult->num_rows === 0) {
  echo "<script>alert('tb_todolist.member_idx 컬럼이 필요합니다. 먼저 DB SQL을 실행해주세요.'); location.href='index.php';</script>";
  exit;
}

$endDateResult = $conn->query("SHOW COLUMNS FROM tb_todolist LIKE 'end_date'");
$hasEndDateColumn = ($endDateResult && $endDateResult->num_rows > 0);

$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');

if ($month < 1) {
  $month = 12;
  $year--;
}

if ($month > 12) {
  $month = 1;
  $year++;
}

$firstDay = sprintf("%04d-%02d-01", $year, $month);
$startWeek = (int)date('w', strtotime($firstDay));
$lastDate = (int)date('t', strtotime($firstDay));

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

$weekNames = ["일", "월", "화", "수", "목", "금", "토"];

$dateSelect = $hasEndDateColumn ? "idx, due_date, end_date, todo_time, title, goal, status" : "idx, due_date, todo_time, title, goal, status";
$monthStart = sprintf("%04d-%02d-01", $year, $month);
$monthEnd = sprintf("%04d-%02d-%02d", $year, $month, $lastDate);

$sql = $hasEndDateColumn
  ? "SELECT $dateSelect
     FROM tb_todolist
     WHERE member_idx = ? AND due_date <= ? AND COALESCE(end_date, due_date) >= ?
     ORDER BY due_date ASC, todo_time ASC, status ASC, idx DESC"
  : "SELECT $dateSelect
     FROM tb_todolist
     WHERE member_idx = ? AND YEAR(due_date) = ? AND MONTH(due_date) = ?
     ORDER BY due_date ASC, todo_time ASC, status ASC, idx DESC";
$stmt = $conn->prepare($sql);
if ($hasEndDateColumn) {
  $stmt->bind_param("iss", $memberIdx, $monthEnd, $monthStart);
} else {
  $stmt->bind_param("iii", $memberIdx, $year, $month);
}
$stmt->execute();
$result = $stmt->get_result();

$todos = [];
while ($row = $result->fetch_assoc()) {
  $startDay = (int)date('j', strtotime($row['due_date']));
  $endDay = $startDay;

  if ($hasEndDateColumn && !empty($row['end_date'])) {
    $endDay = (int)date('j', strtotime($row['end_date']));
  }

  for ($day = max(1, $startDay); $day <= min($lastDate, $endDay); $day++) {
    $todos[$day][] = $row;
  }
}

$today = date("Y-m-d");
$todaySql = $hasEndDateColumn
  ? "SELECT $dateSelect
     FROM tb_todolist
     WHERE member_idx = ? AND due_date <= ? AND COALESCE(end_date, due_date) >= ?
     ORDER BY todo_time ASC, status ASC, idx DESC"
  : "SELECT $dateSelect
     FROM tb_todolist
     WHERE member_idx = ? AND due_date = ?
     ORDER BY todo_time ASC, status ASC, idx DESC";
$todayStmt = $conn->prepare($todaySql);
if ($hasEndDateColumn) {
  $todayStmt->bind_param("iss", $memberIdx, $today, $today);
} else {
  $todayStmt->bind_param("is", $memberIdx, $today);
}
$todayStmt->execute();
$todayResult = $todayStmt->get_result();

$weekStart = date("Y-m-d", strtotime("monday this week"));
$weekEnd = date("Y-m-d", strtotime("sunday this week"));
$weekSql = $hasEndDateColumn
  ? "SELECT $dateSelect
     FROM tb_todolist
     WHERE member_idx = ? AND due_date <= ? AND COALESCE(end_date, due_date) >= ?
     ORDER BY due_date ASC, todo_time ASC, status ASC, idx DESC"
  : "SELECT $dateSelect
     FROM tb_todolist
     WHERE member_idx = ? AND due_date BETWEEN ? AND ?
     ORDER BY due_date ASC, todo_time ASC, status ASC, idx DESC";
$weekStmt = $conn->prepare($weekSql);
if ($hasEndDateColumn) {
  $weekStmt->bind_param("iss", $memberIdx, $weekEnd, $weekStart);
} else {
  $weekStmt->bind_param("iss", $memberIdx, $weekStart, $weekEnd);
}
$weekStmt->execute();
$weekResult = $weekStmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ToDoList 달력</title>
  <link rel="stylesheet" href="css/calendar.css">
  <link rel="stylesheet" href="css/seasonEffect.css">
  <!-- LIGHT -->
  <link id="header-light-theme" rel="stylesheet"href="css/header_bright_pastel_light.css">
  <link id="main-light-theme" rel="stylesheet" href="css/todo_light.css">

  <!--  DARK -->
  <link id="header-dark-theme" rel="stylesheet" href="css/header_glass_mood_light.css" disabled>
  <link id="main-dark-theme" rel="stylesheet" href="css/todo_dark.css" disabled>

  <script src="js/theme-mode.js" defer></script>

  <!-- 테마 JS -->
    <script src="js/seasonEffect.js" defer></script>
  <!-- ToDo 체크 JS -->
    <script src="js/todo-check.js" defer></script>

</head>

<body>
  <div class="theme-bg"></div>
  <?php include "header.php"; ?>

<main class="calendar-main">
  <aside class="side-box">
    <h3>오늘의 일정</h3>

    <?php if ($todayResult->num_rows > 0) { ?>
      <?php while ($row = $todayResult->fetch_assoc()) {
        $checked = ((int)$row['status'] === 1) ? "checked" : "";
        $doneClass = ((int)$row['status'] === 1) ? "done" : "";
        $timeText = !empty($row['todo_time']) ? date("H:i", strtotime($row['todo_time'])) : "";
      ?>
        <div class="side-todo today-side-todo <?= $doneClass ?>">
          <?php if ($timeText !== "") { ?>
            <span class="side-time"><?= htmlspecialchars($timeText, ENT_QUOTES, 'UTF-8') ?></span>
          <?php } ?>
          <input type="checkbox" <?= $checked ?> disabled>
          <span class="side-title"><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>
      <?php } ?>
    <?php } else { ?>
      <p class="empty">오늘 일정이 없습니다.</p>
    <?php } ?>
  </aside>

  <div class="calendar-wrap">
    <div class="calendar-header">
      <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>" aria-label="이전 달">&lt;</a>
      <h2><?= $year ?>.<?= sprintf("%02d", $month) ?></h2>
      <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>" aria-label="다음 달">&gt;</a>
    </div>

    <div class="top-btn">
      <a href="ToDo_insert.php">+ ToDo 추가</a>
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
          $week = (int)date('w', strtotime(sprintf("%04d-%02d-%02d", $year, $month, $day)));
          $class = "";

          if ($week === 0) $class = "sun";
          if ($week === 6) $class = "sat";

          $dateParam = sprintf("%04d-%02d-%02d", $year, $month, $day);

          echo "<td class='calendar-cell' onclick=\"openTodoModal('$dateParam')\">";
          echo "<div class='date $class'>$day</div>";

          if (isset($todos[$day])) {
            foreach ($todos[$day] as $todo) {
              $doneClass = ((int)$todo['status'] === 1) ? "done" : "";
              $checked = ((int)$todo['status'] === 1) ? "checked" : "";
              $timeText = !empty($todo['todo_time']) ? date("H:i", strtotime($todo['todo_time'])) : "";

              echo "<div class='todo-item $doneClass'>";
              echo "<div class='todo-title'>";
              echo "<input type='checkbox' $checked onclick='event.stopPropagation()' disabled>";

              if ($timeText !== "") {
                echo "<span class='todo-time'>" . htmlspecialchars($timeText, ENT_QUOTES, 'UTF-8') . "</span>";
              }

              echo "<span>" . htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8') . "</span>";
              echo "</div>";
              echo "</div>";
            }
          }

          echo "</td>";

          if ($week === 6 && $day !== $lastDate) {
            echo "</tr><tr>";
          }
        }

        $lastWeek = (int)date('w', strtotime(sprintf("%04d-%02d-%02d", $year, $month, $lastDate)));

        for ($i = $lastWeek; $i < 6; $i++) {
          echo "<td></td>";
        }
        ?>
        </tr>
      </tbody>
    </table>
  </div>

  <aside class="side-box">
    <h3>주간 일정</h3>

    <?php if ($weekResult->num_rows > 0) { ?>
      <?php while ($row = $weekResult->fetch_assoc()) {
        $checked = ((int)$row['status'] === 1) ? "checked" : "";
        $doneClass = ((int)$row['status'] === 1) ? "done" : "";
        $dateText = date("m/d", strtotime($row['due_date']));
        $dayText = $weekNames[(int)date("w", strtotime($row['due_date']))];
        $timeText = !empty($row['todo_time']) ? date("H:i", strtotime($row['todo_time'])) : "";
      ?>
        <div class="side-todo week-side-todo <?= $doneClass ?>">
          <span class="side-date"><?= $dateText ?>(<?= $dayText ?>)</span>
          <span class="side-time"><?= htmlspecialchars($timeText, ENT_QUOTES, 'UTF-8') ?></span>
          <input type="checkbox" <?= $checked ?> disabled>
          <span class="side-title"><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>
      <?php } ?>
    <?php } else { ?>
      <p class="empty">이번 주 일정이 없습니다.</p>
    <?php } ?>
  </aside>
</main>

<div class="modal-wrap" id="todoModal" onclick="closeTodoModal()">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-header">
      <h3 id="modalDateTitle">일정</h3>
      <button type="button" class="modal-close" onclick="closeTodoModal()">×</button>
    </div>
    <div id="modalContent" class="modal-content">불러오는 중...</div>
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
      if (response.status === 401) {
        location.href = "login.php";
        return "";
      }
      return response.text();
    })
    .then(function(data) {
      if (data) content.innerHTML = data;
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
