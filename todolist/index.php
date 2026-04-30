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

$todaySql = "SELECT * FROM tb_todolist
             WHERE due_date = ?
             ORDER BY status ASC, idx DESC";

$stmtToday = $conn->prepare($todaySql);
$stmtToday->bind_param("s", $today);
$stmtToday->execute();
$todayResult = $stmtToday->get_result();

$weekSql = "SELECT * FROM tb_todolist
            WHERE due_date BETWEEN ? AND ?
            ORDER BY due_date ASC, status ASC";

$stmtWeek = $conn->prepare($weekSql);
$stmtWeek->bind_param("ss", $weekStart, $weekEnd);
$stmtWeek->execute();
$weekResult = $stmtWeek->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MY TODO MAIN</title>

<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/sakura.css">
<script src="js/sakura.js" defer></script>
</head>

<body>

<?php include "header.php"; ?>


<main>
  <div class="main-wrap">

    <h2 class="main-title">오늘의 계획</h2>

    <div class="plan-area">

      <!-- 오늘 -->
      <div class="card">
        <h3>오늘의 할 일</h3>

        <?php if ($todayResult->num_rows > 0) { ?>
          <?php while ($row = $todayResult->fetch_assoc()) {
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

      <!-- 주간 -->
      <div class="card">
        <h3>주간 계획표</h3>

        <?php if ($weekResult->num_rows > 0) { ?>
          <?php while ($row = $weekResult->fetch_assoc()) {
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