<?php
session_start();
require_once "dbcon.php";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$today = date("Y-m-d");
$weekStart = date("Y-m-d", strtotime("monday this week"));
$weekEnd = date("Y-m-d", strtotime("sunday this week"));

/* 오늘 일정 */
$todaySql = "SELECT * FROM tb_todolist
             WHERE due_date = ?
             ORDER BY status ASC, idx DESC";

$stmtToday = $conn->prepare($todaySql);
$stmtToday->bind_param("s", $today);
$stmtToday->execute();
$todayResult = $stmtToday->get_result();

$todayData = [];
$todayDoneCount = 0;

while ($row = $todayResult->fetch_assoc()) {
  $todayData[] = $row;
  if ($row['status'] == 1) {
    $todayDoneCount++;
  }
}

$todayTotalCount = count($todayData);
$todayPercent = $todayTotalCount > 0 ? round(($todayDoneCount / $todayTotalCount) * 100) : 0;

/* 주간 일정 */
$weekSql = "SELECT * FROM tb_todolist
            WHERE due_date BETWEEN ? AND ?
            ORDER BY due_date ASC, status ASC";

$stmtWeek = $conn->prepare($weekSql);
$stmtWeek->bind_param("ss", $weekStart, $weekEnd);
$stmtWeek->execute();
$weekResult = $stmtWeek->get_result();

$weekData = [];
$weekDoneCount = 0;

while ($row = $weekResult->fetch_assoc()) {
  $weekData[] = $row;
  if ($row['status'] == 1) {
    $weekDoneCount++;
  }
}

$weekTotalCount = count($weekData);
$weekPercent = $weekTotalCount > 0 ? round(($weekDoneCount / $weekTotalCount) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MY TODO MAIN</title>

<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/seasonEffect.css">
<script src="js/seasonEffect.js" defer></script>
</head>

<body>

<?php include "header.php"; ?>

<main>
  <div class="main-wrap">

    <h2 class="main-title">오늘의 계획</h2>

    <div class="main-desc">
      <strong>오늘의 할 일과 주간 계획을 한눈에 확인해보세요.</strong>
      <span>완료율을 보며 남은 일정을 관리할 수 있어요.</span>
    </div>

    <div class="progress-area">

      <div class="progress-box">
        <div class="progress-text">
          오늘 완료율 <?= $todayPercent ?>% (<?= $todayDoneCount ?> / <?= $todayTotalCount ?>)
        </div>
        <div class="progress-bar">
          <div class="progress-fill" style="width: <?= $todayPercent ?>%;"></div>
        </div>
      </div>

      <div class="progress-box">
        <div class="progress-text">
          주간 완료율 <?= $weekPercent ?>% (<?= $weekDoneCount ?> / <?= $weekTotalCount ?>)
        </div>
        <div class="progress-bar">
          <div class="progress-fill week-fill" style="width: <?= $weekPercent ?>%;"></div>
        </div>
      </div>

    </div>

      <?php if ($todayTotalCount > 0 && $todayPercent == 100) { ?>
      <div class="success-box">
        🎉 오늘 목표를 모두 완료했어요!
      </div>
      <?php } ?>

    <div class="plan-area">

      <div class="card">
        <h3>오늘의 할 일</h3>

        <?php if ($todayTotalCount > 0) { ?>
          <?php foreach ($todayData as $row) {
            $checked = $row['status'] == 1 ? "checked" : "";
            $doneClass = $row['status'] == 1 ? "done" : "";
          ?>
            <div class="todo <?= $doneClass ?>">
              <input type="checkbox" <?= $checked ?> disabled>
              <span><?= htmlspecialchars($row['title']) ?></span>
            </div>
          <?php } ?>
        <?php } else { ?>
          <p class="empty">오늘 할 일이 없습니다.</p>
        <?php } ?>
      </div>

      <div class="card">
        <h3>주간 계획표</h3>

        <?php if ($weekTotalCount > 0) { ?>
          <?php foreach ($weekData as $row) {
            $checked = $row['status'] == 1 ? "checked" : "";
            $doneClass = $row['status'] == 1 ? "done" : "";
            $dateText = date("m/d", strtotime($row['due_date']));
          ?>
            <div class="todo <?= $doneClass ?>">
              <span class="todo-date"><?= $dateText ?></span>
              <input type="checkbox" <?= $checked ?> disabled>
              <span><?= htmlspecialchars($row['title']) ?></span>
            </div>
          <?php } ?>
        <?php } else { ?>
          <p class="empty">주간 계획이 없습니다.</p>
        <?php } ?>
      </div>

    </div>

    <a href="ToDo_list.php" class="go-btn sparkle-btn">월별계획표 보기</a>

  </div>
</main>

</body>
</html>

<?php
$stmtToday->close();
$stmtWeek->close();
$conn->close();
?>