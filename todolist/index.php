<?php
session_start();
require_once "dbcon.php";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

/* 로그인 여부 */
$isLogin = isset($_SESSION['idx']);

/* 로그인한 경우에만 개인 일정 데이터 조회 */
if ($isLogin) {
  $memberIdx = $_SESSION['idx'];

  $today = date("Y-m-d");
  $weekStart = date("Y-m-d", strtotime("monday this week"));
  $weekEnd = date("Y-m-d", strtotime("sunday this week"));

  /* 오늘 일정 */
  $todaySql = "SELECT * FROM tb_todolist
               WHERE member_idx = ?
               AND due_date = ?
               ORDER BY status ASC, todo_time ASC, idx DESC";

  $stmtToday = $conn->prepare($todaySql);
  $stmtToday->bind_param("is", $memberIdx, $today);
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
              WHERE member_idx = ?
              AND due_date BETWEEN ? AND ?
              ORDER BY due_date ASC, todo_time ASC, status ASC, idx DESC";

  $stmtWeek = $conn->prepare($weekSql);
  $stmtWeek->bind_param("iss", $memberIdx, $weekStart, $weekEnd);
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

  /* 대시보드 전체 통계 */
  $totalSql = "SELECT 
                COUNT(*) AS total_count,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS done_count,
                SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) AS not_done_count
               FROM tb_todolist
               WHERE member_idx = ?";

  $stmtTotal = $conn->prepare($totalSql);
  $stmtTotal->bind_param("i", $memberIdx);
  $stmtTotal->execute();
  $totalResult = $stmtTotal->get_result();
  $totalRow = $totalResult->fetch_assoc();

  $totalCount = $totalRow['total_count'] ?? 0;
  $doneCount = $totalRow['done_count'] ?? 0;
  $notDoneCount = $totalRow['not_done_count'] ?? 0;
}
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

    <!-- Hero 이미지 배너 -->
    <section class="hero-banner">
      <img src="images/back.jpg" alt="planner background">

      <div class="hero-overlay">
        <p class="hero-label">MY TODO</p>
        <h2>오늘의 계획을<br>한눈에 관리하세요</h2>
        <span>나만의 일정과 목표를 감성적으로 기록하는 ToDo 서비스</span>
      </div>
    </section>

    <?php if ($isLogin) { ?>

      <!-- 로그인 사용자 화면 -->
      <h2 class="main-title">오늘의 계획</h2>

      <div class="main-desc">
        <strong>오늘의 할 일과 주간 계획을 한눈에 확인해보세요.</strong>
        <span>완료율을 보며 남은 일정을 관리할 수 있어요.</span>
      </div>

      <!-- 대시보드 카드 -->
      <div class="dashboard-area">

        <div class="dash-card">
          <span class="dash-label">전체 일정</span>
          <strong><?= $totalCount ?></strong>
          <p>등록된 전체 ToDo</p>
        </div>

        <div class="dash-card">
          <span class="dash-label">완료 일정</span>
          <strong><?= $doneCount ?></strong>
          <p>완료한 일정</p>
        </div>

        <div class="dash-card">
          <span class="dash-label">미완료</span>
          <strong><?= $notDoneCount ?></strong>
          <p>남은 일정</p>
        </div>

        <div class="dash-card">
          <span class="dash-label">이번 주</span>
          <strong><?= $weekTotalCount ?></strong>
          <p>주간 계획 수</p>
        </div>

      </div>

      <!-- 완료율 -->
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

      <!-- 오늘 / 주간 일정 카드 -->
      <div class="plan-area">

        <div class="card">
          <h3>오늘의 할 일</h3>

          <?php if ($todayTotalCount > 0) { ?>
            <?php foreach ($todayData as $row) {
              $checked = $row['status'] == 1 ? "checked" : "";
              $doneClass = $row['status'] == 1 ? "done" : "";
              $timeText = !empty($row['todo_time']) ? date("H:i", strtotime($row['todo_time'])) : "";
            ?>
              <div class="todo <?= $doneClass ?>">
                <input type="checkbox" <?= $checked ?> disabled>

                <?php if ($timeText !== "") { ?>
                  <span class="todo-date"><?= $timeText ?></span>
                <?php } ?>

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
              $timeText = !empty($row['todo_time']) ? date("H:i", strtotime($row['todo_time'])) : "";
            ?>
              <div class="todo <?= $doneClass ?>">
                <span class="todo-date">
                  <?= $dateText ?><?= $timeText !== "" ? " " . $timeText : "" ?>
                </span>

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

    <?php } else { ?>

      <!-- 비회원 화면 -->
      <div class="guest-box">

        <h2>감성적인 일정 관리 서비스</h2>

        <p>
          오늘의 할 일과 주간 계획을 기록하고,<br>
          나만의 목표를 한눈에 관리해보세요.
        </p>

        <div class="guest-feature">

          <div class="feature-card">
            <strong>📅 월별 계획표</strong>
            <span>달력에서 날짜별 일정을 관리할 수 있어요.</span>
          </div>

          <div class="feature-card">
            <strong>✅ 완료 체크</strong>
            <span>일정을 완료하면 상태를 바로 확인할 수 있어요.</span>
          </div>

          <div class="feature-card">
            <strong>📊 완료율 시각화</strong>
            <span>오늘과 주간 완료율을 한눈에 볼 수 있어요.</span>
          </div>

          <div class="feature-card">
            <strong>🌸 감성 UI</strong>
            <span>핑크톤 플래너 스타일로 구성했어요.</span>
          </div>

        </div>

        <div class="guest-btn-area">
          <a href="login.php" class="guest-btn">로그인</a>
          <a href="join.php" class="guest-btn join-btn">회원가입</a>
        </div>

      </div>

    <?php } ?>

  </div>
</main>

</body>
</html>

<?php
if ($isLogin) {
  $stmtToday->close();
  $stmtWeek->close();
  $stmtTotal->close();
}

$conn->close();
?>