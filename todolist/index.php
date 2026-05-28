<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "dbcon.php";
require_once "config/api.php";

$isLogin = isset($_SESSION['idx'], $_SESSION['loginid']);

$todayData = [];
$todayPercent = 0;
$todayTotalCount = 0;
$totalCount = 0;
$doneCount = 0;
$notDoneCount = 0;
$weekTotalCount = 0;
$monthTodos = [];
$calendarTodos = [];
$rangeBars = [];
$rangePalette = ["range-pink", "range-rose", "range-purple", "range-peach"];

$playlistTitle = "";
$playlistUrl = "";
$playlistEmbedUrl = "";

$coverTitle = "독서 하면서\n느낀 감정들을 정리해요";
$coverSub = "작은 기록이 내일의 나를 만듭니다.";

$year = (int)date("Y");
$month = (int)date("m");
$firstDay = sprintf("%04d-%02d-01", $year, $month);
$startWeek = (int)date("w", strtotime($firstDay));
$lastDate = (int)date("t", strtotime($firstDay));

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$needsTodoMemberColumn = false;
$hasEndDateColumn = false;

if ($isLogin) {
  $memberIdx = (int)$_SESSION['idx'];

  $columnResult = $conn->query("SHOW COLUMNS FROM tb_todolist LIKE 'member_idx'");
  $needsTodoMemberColumn = (!$columnResult || $columnResult->num_rows === 0);

  $endDateResult = $conn->query("SHOW COLUMNS FROM tb_todolist LIKE 'end_date'");
  $hasEndDateColumn = ($endDateResult && $endDateResult->num_rows > 0);
}

function youtubeEmbedUrl($url) {
  if (!$url) return "";

  if (preg_match('/youtu\.be\/([^?&]+)/', $url, $matches)) {
    return "https://www.youtube.com/embed/" . $matches[1];
  }

  if (preg_match('/v=([^?&]+)/', $url, $matches)) {
    return "https://www.youtube.com/embed/" . $matches[1];
  }

  if (preg_match('/list=([^?&]+)/', $url, $matches)) {
    return "https://www.youtube.com/embed/videoseries?list=" . $matches[1];
  }

  return "";
}

function buildRangeBars($rows, $year, $month, $lastDate, $hasEndDateColumn, $rangePalette) {
  $bars = [];
  $groups = [];

  foreach ($rows as $row) {
    $startDay = (int)date("j", strtotime($row['due_date']));
    $endDay = $startDay;

    if ($hasEndDateColumn && !empty($row['end_date'])) {
      $endDay = (int)date("j", strtotime($row['end_date']));
    }

    $key = md5($row['title'] . '|' . ($row['goal'] ?? '') . '|' . (int)$row['status']);

    if (!isset($groups[$key])) {
      $groups[$key] = [
      'idx' => (int)$row['idx'],
      'title' => $row['title'],
      'status' => (int)$row['status'],
      'days' => [],
    ];
    }

    for ($day = max(1, $startDay); $day <= min($lastDate, $endDay); $day++) {
      $groups[$key]['days'][$day] = true;
    }
  }

  $barIndex = 0;

  foreach ($groups as $group) {
    $days = array_keys($group['days']);
    sort($days);

    $segment = [];
    $prev = null;

    foreach ($days as $day) {
      if ($prev !== null && $day !== $prev + 1) {
        if (count($segment) >= 2) {
          addRangeSegment($bars, $segment, $group, $barIndex, $rangePalette);
          $barIndex++;
        }

        $segment = [];
      }

      $segment[] = $day;
      $prev = $day;
    }

    if (count($segment) >= 2) {
      addRangeSegment($bars, $segment, $group, $barIndex, $rangePalette);
      $barIndex++;
    }
  }

  return $bars;
}

function addRangeSegment(&$bars, $segment, $group, $barIndex, $rangePalette) {
  $count = count($segment);
  $class = $rangePalette[$barIndex % count($rangePalette)];

  foreach ($segment as $i => $day) {
    if (!isset($bars[$day])) {
      $bars[$day] = [];
    }

    $bars[$day][] = [
      'title' => $group['title'],
      'class' => $class,
      'part' => $i === 0 ? 'start' : ($i === $count - 1 ? 'end' : 'mid'),
      'showLabel' => $i === 0,
      'done' => $group['status'] === 1,
    ];
  }
}

if ($isLogin && !$needsTodoMemberColumn) {
  $today = date("Y-m-d");
  $weekStart = date("Y-m-d", strtotime("monday this week"));
  $weekEnd = date("Y-m-d", strtotime("sunday this week"));

  $playlistSql = "SELECT playlist_title, playlist_url FROM tb_member WHERE idx = ?";
  $stmtPlaylist = $conn->prepare($playlistSql);
  $stmtPlaylist->bind_param("i", $memberIdx);
  $stmtPlaylist->execute();
  $playlistRow = $stmtPlaylist->get_result()->fetch_assoc();

  $playlistTitle = $playlistRow['playlist_title'] ?? "";
  $playlistUrl = $playlistRow['playlist_url'] ?? "";
  $playlistEmbedUrl = youtubeEmbedUrl($playlistUrl);

  $coverSql = "SELECT title_text, sub_text FROM user_cover_texts WHERE user_idx = ? LIMIT 1";
  $stmtCover = $conn->prepare($coverSql);
  $stmtCover->bind_param("i", $memberIdx);
  $stmtCover->execute();
  $coverRow = $stmtCover->get_result()->fetch_assoc();

  if ($coverRow) {
    $coverTitle = $coverRow['title_text'] ?: $coverTitle;
    $coverSub = $coverRow['sub_text'] ?: $coverSub;
  }

  $dateSelect = $hasEndDateColumn
    ? "idx, due_date, end_date, todo_time, title, goal, status"
    : "idx, due_date, todo_time, title, goal, status";

  $todayWhere = $hasEndDateColumn
    ? "member_idx = ? AND due_date <= ? AND COALESCE(end_date, due_date) >= ?"
    : "member_idx = ? AND due_date = ?";

  $todaySql = "SELECT $dateSelect
               FROM tb_todolist
               WHERE $todayWhere
               ORDER BY status ASC, todo_time ASC, idx DESC";

  $stmtToday = $conn->prepare($todaySql);

  if ($hasEndDateColumn) {
    $stmtToday->bind_param("iss", $memberIdx, $today, $today);
  } else {
    $stmtToday->bind_param("is", $memberIdx, $today);
  }

  $stmtToday->execute();
  $todayResult = $stmtToday->get_result();

  $todayDoneCount = 0;

  while ($row = $todayResult->fetch_assoc()) {
    $todayData[] = $row;

    if ((int)$row['status'] === 1) {
      $todayDoneCount++;
    }
  }

  $todayTotalCount = count($todayData);
  $todayPercent = $todayTotalCount > 0 ? (int)round(($todayDoneCount / $todayTotalCount) * 100) : 0;

  $weekSql = $hasEndDateColumn
    ? "SELECT COUNT(*) AS week_count FROM tb_todolist WHERE member_idx = ? AND due_date <= ? AND COALESCE(end_date, due_date) >= ?"
    : "SELECT COUNT(*) AS week_count FROM tb_todolist WHERE member_idx = ? AND due_date BETWEEN ? AND ?";

  $stmtWeek = $conn->prepare($weekSql);

  if ($hasEndDateColumn) {
    $stmtWeek->bind_param("iss", $memberIdx, $weekEnd, $weekStart);
  } else {
    $stmtWeek->bind_param("iss", $memberIdx, $weekStart, $weekEnd);
  }

  $stmtWeek->execute();
  $weekRow = $stmtWeek->get_result()->fetch_assoc();
  $weekTotalCount = (int)($weekRow['week_count'] ?? 0);

  $totalSql = "SELECT
                 COUNT(*) AS total_count,
                 SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS done_count,
                 SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) AS not_done_count
               FROM tb_todolist
               WHERE member_idx = ?";

  $stmtTotal = $conn->prepare($totalSql);
  $stmtTotal->bind_param("i", $memberIdx);
  $stmtTotal->execute();
  $totalRow = $stmtTotal->get_result()->fetch_assoc();

  $totalCount = (int)($totalRow['total_count'] ?? 0);
  $doneCount = (int)($totalRow['done_count'] ?? 0);
  $notDoneCount = (int)($totalRow['not_done_count'] ?? 0);

  $monthSql = $hasEndDateColumn
    ? "SELECT $dateSelect
       FROM tb_todolist
       WHERE member_idx = ? AND due_date <= ? AND COALESCE(end_date, due_date) >= ?
       ORDER BY due_date ASC, todo_time ASC, status ASC, idx DESC"
    : "SELECT $dateSelect
       FROM tb_todolist
       WHERE member_idx = ? AND YEAR(due_date) = ? AND MONTH(due_date) = ?
       ORDER BY due_date ASC, todo_time ASC, status ASC, idx DESC";

  $stmtMonth = $conn->prepare($monthSql);

  if ($hasEndDateColumn) {
    $monthStart = sprintf("%04d-%02d-01", $year, $month);
    $monthEnd = sprintf("%04d-%02d-%02d", $year, $month, $lastDate);
    $stmtMonth->bind_param("iss", $memberIdx, $monthEnd, $monthStart);
  } else {
    $stmtMonth->bind_param("iii", $memberIdx, $year, $month);
  }

  $stmtMonth->execute();
  $monthResult = $stmtMonth->get_result();

  while ($row = $monthResult->fetch_assoc()) {
    $startDay = (int)date("j", strtotime($row['due_date']));
    $endDay = $startDay;

    if ($hasEndDateColumn && !empty($row['end_date'])) {
      $endDay = (int)date("j", strtotime($row['end_date']));
    }

    for ($day = max(1, $startDay); $day <= min($lastDate, $endDay); $day++) {
      $monthTodos[$day] = ($monthTodos[$day] ?? 0) + 1;
      $calendarTodos[$day][] = $row;
    }
  }

  $rangeBars = buildRangeBars(array_merge(...array_values($calendarTodos ?: [[]])), $year, $month, $lastDate, $hasEndDateColumn, $rangePalette);
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MY TODO MAIN</title>

  <!-- Pretendard -->
  <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" />

  <!-- Cafe24 -->
  <link href="https://webfontworld.github.io/cafe24/Cafe24Ssurround.css" rel="stylesheet">

  <link id="main-light-theme" rel="stylesheet" href="css/main_bright_pastel_light.css">
  <link id="main-dark-theme" rel="stylesheet" href="css/main_glass_mood_light.css" disabled>

  <script>
  window.TODO_CONFIG = {
    OPENWEATHER_KEY: "<?= defined('OPENWEATHER_KEY') ? OPENWEATHER_KEY : '' ?>",
    WEATHER_CITY: "Seoul",
    KAKAO_REST_KEY: "<?= defined('KAKAO_REST_KEY') ? KAKAO_REST_KEY : '' ?>"
  };
  </script>

  <script src="js/theme-mode.js" defer></script>
  <script src="js/weather-widget.js" defer></script>
  <script src="js/book-widget.js" defer></script>
  <script src="js/dashboard-ui.js" defer></script>
  
  <script src="js/todo-check.js" defer></script>
</head>

<body>
  <div class="theme-bg"></div>

  <?php include "header.php"; ?>

  <main>
    <div class="main-wrap dashboard-wrap">
      <section class="hero-banner" id="top">
        <img id="coverImage" src="images/back.jpg" alt="planner cover">

        <div class="hero-overlay">
          <p class="hero-label">MY TODO</p>

          <h2 id="coverTitle">
            <?= nl2br(htmlspecialchars($coverTitle, ENT_QUOTES, 'UTF-8')) ?>
          </h2>

          <span id="coverSub">
            <?= htmlspecialchars($coverSub, ENT_QUOTES, 'UTF-8') ?>
          </span>

          <?php if (isset($_SESSION["idx"])) { ?>
            <form class="cover-edit-form" method="post" action="cover_save.php">
              <input type="text" name="title_text" placeholder="자신만의 슬로건 입력" value="<?= htmlspecialchars(str_replace("\n", " ", $coverTitle), ENT_QUOTES, 'UTF-8') ?>" required>
              <input type="text" name="sub_text" placeholder="오늘 하루 좌우명 입력" value="<?= htmlspecialchars($coverSub, ENT_QUOTES, 'UTF-8') ?>" required>
              <button type="submit">저장</button>
            </form>
          <?php } ?>
        </div>
      </section>

      <?php if ($isLogin && $needsTodoMemberColumn) { ?>
        <section class="setup-box">
          <h2>일정 테이블 설정이 필요합니다</h2>
          <p>회원별 일정 관리를 위해 <strong>tb_todolist.member_idx</strong> 컬럼을 먼저 추가해주세요.</p>
          <pre><code>ALTER TABLE tb_todolist ADD member_idx INT NULL AFTER idx;
              SELECT idx, id FROM tb_member;
              UPDATE tb_todolist SET member_idx = 1 WHERE member_idx IS NULL;</code></pre>
        </section>
      <?php } elseif ($isLogin) { ?>
        <section class="widget-layout">
          <aside class="left-widgets">
            <div class="mini-widget clock-widget">
              <h3>Clock</h3>
              <strong id="clockText">00:00</strong>
              <span><?= date("Y.m.d") ?></span>
            </div>

            <div class="mini-widget navigator-widget">
              <h3>Navigator</h3>

              <div class="navigator-tabs" role="tablist" aria-label="기록 네비게이터">
                <button type="button" class="active" data-target="memo-section" data-message="오늘의 짧은 메모를 남겨보세요.">독서 정리노트</button>
                <button type="button" data-target="weekly-section" data-message="이번 주의 흐름을 가볍게 돌아보세요.">주간 기록</button>
                <button type="button" data-target="monthly-section" data-message="이번 달의 계획과 결과를 정리해보세요.">월별 기록</button>
                <button type="button" onclick="window.location.href='QnA.php';">QnA</button>
                <button type="button" onclick="window.location.href='notice.php';">공지사항</button>
              </div>

              <p id="navigatorMessage" class="navigator-message">오늘의 짧은 메모를 남겨보세요.</p>
            </div>

            <div class="mini-widget progress-widget" id="today-progress-widget">
              <h3>오늘 완료율</h3>
              <strong id="completePercent">
                <?= $todayPercent ?>%
              </strong>
              <div class="progress-track">
                <div class="progress-fill" id="completeBar" style="width: <?= $todayPercent ?>%;"></div>
              </div>
            </div>

            <div class="mini-widget todo-widget" id="today-todo-widget">
              <h3>오늘 할 일</h3>

              <?php if ($todayTotalCount > 0) { ?>
                <?php foreach ($todayData as $row) {
                  $checked = ((int)$row['status'] === 1) ? "checked" : "";
                  $doneClass = ((int)$row['status'] === 1) ? "done" : "";
                ?>
                  <label class="todo <?= $doneClass ?>">
                    <input type="checkbox" class="todo-check" data-idx="<?= $row['idx'] ?>" 
                    <?= $checked ?>>
                    <span class="todo-title <?= $doneClass ?>">
                      <?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                  </label>
                <?php } ?>
              <?php } else { ?>
                <p class="empty">오늘 할 일이 없습니다.</p>
              <?php } ?>
            </div>
          </aside>

          <section class="center-calendar" id="dashboard-calendar">
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

            <div class="mini-calendar-box">
              <div class="mini-calendar-header">
                <h2><?= $year ?>.<?= sprintf("%02d", $month) ?></h2>
                <a href="ToDo_list.php" class="small-link">월별계획표 보기</a>
              </div>

              <table class="mini-calendar">
                <thead>
                  <tr>
                    <th>일</th>
                    <th>월</th>
                    <th>화</th>
                    <th>수</th>
                    <th>목</th>
                    <th>금</th>
                    <th>토</th>
                  </tr>
                </thead>

                <tbody>
                  <tr>
                  <?php
                  for ($i = 0; $i < $startWeek; $i++) {
                    echo "<td></td>";
                  }

                  for ($day = 1; $day <= $lastDate; $day++) {
                    $week = (int)date("w", strtotime(sprintf("%04d-%02d-%02d", $year, $month, $day)));
                    $todayClass = ($day === (int)date("j")) ? "today-cell" : "";
                    $hasRanges = isset($rangeBars[$day]) ? "has-range" : "";

                    echo "<td class='$todayClass $hasRanges'>";
                    echo "<a href='ToDo_list.php?year=$year&month=$month'>";
                    echo "<span>$day</span>";

                    if (isset($rangeBars[$day])) {
                      foreach ($rangeBars[$day] as $bar) {
                        $barClass = $bar['class'] . " range-" . $bar['part'] . ($bar['done'] ? " range-done" : "");
                        echo "<i class='range-bar $barClass'>";
                        echo $bar['showLabel'] ? htmlspecialchars($bar['title'], ENT_QUOTES, 'UTF-8') : "";
                        echo "</i>";
                      }
                    }

                    if (isset($calendarTodos[$day])) {

                      echo "<div class='calendar-preview-list'>";

                      foreach (array_slice($calendarTodos[$day], 0, 2) as $todo) {

                        echo "<p class='calendar-preview-item'>";

                        echo htmlspecialchars(
                          $todo['title'],
                          ENT_QUOTES,
                          'UTF-8'
                        );

                        echo "</p>";
                      }

                      echo "</div>";
                    }

                    if (isset($monthTodos[$day])) {
                      echo "<em>{$monthTodos[$day]}</em>";
                    }

                    echo "</a>";
                    echo "</td>";

                    if ($week === 6 && $day !== $lastDate) {
                      echo "</tr><tr>";
                    }
                  }

                  $lastWeek = (int)date("w", strtotime(sprintf("%04d-%02d-%02d", $year, $month, $lastDate)));

                  for ($i = $lastWeek; $i < 6; $i++) {
                    echo "<td></td>";
                  }
                  ?>
                  </tr>
                </tbody>
              </table>
            </div>

            <section class="record-section" id="memo-section">
              <h3>독서 정리 노트</h3>
              <p>독서하면서 느낀 점과 소감을 짧게 정리하는 공간입니다.</p>
            </section>

            <section class="record-section" id="weekly-section">
              <h3>주간 기록</h3>
              <p>이번 주에 진행한 일정과 남은 할 일을 돌아보는 공간입니다.</p>
            </section>

            <section class="record-section" id="monthly-section">
              <h3>월별 기록</h3>
              <p>한 달의 큰 흐름과 기간 일정을 색 줄로 확인하는 공간입니다.</p>
            </section>

            <section class="record-section" id="qna-section">
              <h3>QnA</h3>
              <p>궁금한 내용을 정리해두는 공간입니다.</p>
            </section>

            <section class="record-section" id="notice-section">
              <h3>공지사항</h3>
              <p>중요한 안내와 업데이트를 확인하는 공간입니다.</p>
            </section>
          </section>

          <aside class="right-widgets">
            <div class="mini-widget weather-widget">
              <h3>Seoul Weather</h3>

              <div id="weatherBox" class="api-widget-body">
                <p class="api-fallback">날씨 정보를 불러올 수 없습니다.</p>
              </div>
            </div>

            <div class="mini-widget book-widget glass-card">
              <h3>Book</h3>

              <div class="book-search">
                <input type="text" id="bookSearchInput" placeholder="도서 검색">
                <button type="button" id="bookSearchBtn">검색</button>
              </div>

              <div id="bookSearchResults" class="book-results"></div>

              <div class="book-content" id="bookBox">
                <div class="book-cover">BOOK</div>

                <div class="book-info">
                  <p>Book Recommend</p>
                  <strong>도서를 검색해보세요</strong>
                  <span>원하는 책을 선택할 수 있어요</span>
                </div>
              </div>
            </div>

            <div class="mini-widget playlist-widget">
              <h3>Playlist</h3>

              <form action="playlist_save.php" method="post" class="playlist-form">
                <input type="text" name="playlist_title" placeholder="플레이리스트 제목" value="<?= htmlspecialchars($playlistTitle, ENT_QUOTES, 'UTF-8') ?>">
                <input type="url" name="playlist_url" placeholder="유튜브 링크 붙여넣기" value="<?= htmlspecialchars($playlistUrl, ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit">저장</button>
              </form>

              <?php if (!empty($playlistEmbedUrl)) { ?>
                <iframe
                  src="<?= htmlspecialchars($playlistEmbedUrl, ENT_QUOTES, 'UTF-8') ?>"
                  title="<?= htmlspecialchars($playlistTitle ?: 'playlist', ENT_QUOTES, 'UTF-8') ?>"
                  allow="autoplay; encrypted-media"
                  allowfullscreen>
                </iframe>
              <?php } else { ?>
                <p class="empty">저장된 플레이리스트가 없습니다.</p>
              <?php } ?>
            </div>
          </aside>
        </section>
      <?php } else { ?>
        <div class="guest-box">
          <h2>감성적인 일정 관리 서비스</h2>

          <p>
            오늘과 이번 달 계획을 한눈에 확인해보세요.<br>
            완료율을 보며 남은 일정을 가볍게 관리할 수 있어요.
          </p>

          <div class="guest-feature">
            <div class="feature-card">
              <strong>월별 계획표</strong>
              <span>달력에서 날짜별 일정을 관리할 수 있어요.</span>
            </div>

            <div class="feature-card">
              <strong>완료 체크</strong>
              <span>일정 상태를 확인하며 하루를 정리해요.</span>
            </div>

            <div class="feature-card">
              <strong>위젯 대시보드</strong>
              <span>오늘의 할 일과 완료율을 한눈에 봅니다.</span>
            </div>

            <div class="feature-card">
              <strong>좋아하는 음악과 책을 기록하세요</strong>
              <span>독서노트 정리를 하면서 음악을 들을 수 있어요.</span>
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
if ($isLogin && !$needsTodoMemberColumn) {
  $stmtToday->close();
  $stmtWeek->close();
  $stmtTotal->close();
  $stmtMonth->close();
  $stmtPlaylist->close();
  $stmtCover->close();
}

$conn->close();
?>