<?php
date_default_timezone_set('Asia/Seoul');
session_start();
require_once "dbcon.php";

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

  $dateSelect = $hasEndDateColumn ? "idx, due_date, end_date, todo_time, title, goal, status" : "idx, due_date, todo_time, title, goal, status";
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
  <!-- 라이트 모드 CSS -->
  <link id="header-light-theme" rel="stylesheet" href="css/header_bright_pastel_light.css">
  <link id="main-light-theme" rel="stylesheet" href="css/main_bright_pastel_light.css">

  <!-- 다크 모드 CSS: 처음에는 꺼두고 버튼 클릭 시 JS로 켭니다 -->
  <link id="header-dark-theme" rel="stylesheet" href="css/header_glass_mood_light.css" disabled>
  <link id="main-dark-theme" rel="stylesheet" href="css/main_glass_mood_light.css" disabled>

  <script src="js/seasonEffect.js" defer></script>
</head>

<body>
<?php include "header.php"; ?>

<main>
  <div class="main-wrap dashboard-wrap">
    <section class="hero-banner" id="top">
      <img id="coverImage" src="images/back.jpg" alt="planner cover">
      <div class="hero-overlay">
        <p class="hero-label">MY TODO</p>
        <h2>오늘의 기록을<br>부드럽게 정리해요</h2>
        <span>작은 기록이 내일의 나를 만듭니다.</span>
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
              <button type="button" class="active" data-target="memo-section" data-message="오늘의 짧은 메모를 남겨보세요.">기록 메모</button>
              <button type="button" data-target="weekly-section" data-message="이번 주의 흐름을 가볍게 돌아보세요.">주간 기록</button>
              <button type="button" data-target="monthly-section" data-message="이번 달의 계획과 결과를 정리해보세요.">월별 기록</button>
              <button type="button" data-target="qna-section" data-message="궁금한 점을 모아두는 공간입니다.">QnA</button>
              <button type="button" data-target="notice-section" data-message="새로운 안내를 확인하는 공간입니다.">공지사항</button>
            </div>
            <p id="navigatorMessage" class="navigator-message">오늘의 짧은 메모를 남겨보세요.</p>
          </div>

          <div class="mini-widget progress-widget" id="today-progress-widget">
            <h3>오늘 완료율</h3>
            <strong><?= $todayPercent ?>%</strong>
            <div class="progress-bar">
              <div class="progress-fill" style="width: <?= $todayPercent ?>%;"></div>
            </div>
          </div>

          <div class="mini-widget todo-widget" id="today-todo-widget">
            <h3>오늘 할 일</h3>
            <?php if ($todayTotalCount > 0) { ?>
              <?php foreach ($todayData as $row) {
                $checked = ((int)$row['status'] === 1) ? "checked" : "";
                $doneClass = ((int)$row['status'] === 1) ? "done" : "";
              ?>
                <div class="todo <?= $doneClass ?>">
                  <input type="checkbox" <?= $checked ?> disabled>
                  <span><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
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

            <!-- 공휴일/임시공휴일 표시는 Google Calendar API 또는 공공데이터 API 연동으로 확장 가능 -->
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
            <h3>기록 메모</h3>
            <p>오늘 느낀 점과 준비할 내용을 짧게 정리하는 공간입니다.</p>
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
          <div class="mini-widget mode-widget">
            <h3>Mode</h3>
            <div class="mode-toggle" aria-label="화면 모드 선택">
              <button type="button" data-theme="light">Light</button>
              <button type="button" data-theme="dark">Dark</button>
            </div>
          </div>

          <div class="mini-widget weather-widget">
            <h3>Seoul Weather</h3>
            <div id="weatherBox" class="api-widget-body">
              <p class="api-fallback">날씨 정보를 불러올 수 없습니다.</p>
            </div>
          </div>

          <div class="mini-widget book-widget">
            <h3>Book</h3>
            <div id="bookBox" class="api-widget-body">
              <p class="api-fallback">추천 도서를 불러올 수 없습니다.</p>
            </div>
          </div>

          <div class="mini-widget playlist-widget">
            <h3>Playlist</h3>
            <iframe
              src="https://www.youtube.com/embed/jfKfPfyJRdk"
              title="playlist"
              allow="autoplay; encrypted-media"
              allowfullscreen>
            </iframe>
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
            <strong>핑크 글래스 UI</strong>
            <span>부드러운 파스텔 톤으로 편안하게 기록해요.</span>
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

<script>
  // 도서
const OPENWEATHER_KEY = ""; // 여기에_발급받은_API_KEY
const WEATHER_CITY = "Seoul";

const weatherBox = document.getElementById("weatherBox");
const themeButtons = document.querySelectorAll(".mode-toggle button[data-theme]");

function applyTheme(theme) {
  const headerLight = document.getElementById("header-light-theme");
  const mainLight = document.getElementById("main-light-theme");
  const headerDark = document.getElementById("header-dark-theme");
  const mainDark = document.getElementById("main-dark-theme");

  const isDark = theme === "dark";

  if (headerLight) headerLight.disabled = isDark;
  if (mainLight) mainLight.disabled = isDark;
  if (headerDark) headerDark.disabled = !isDark;
  if (mainDark) mainDark.disabled = !isDark;

  document.body.classList.remove("light-mode", "dark-mode");
  document.body.classList.add(theme + "-mode");

  localStorage.setItem("todo-theme", theme);

  themeButtons.forEach(function(button) {
    button.classList.toggle("active", button.dataset.theme === theme);
  });
}

function initTheme() {
  const savedTheme = localStorage.getItem("todo-theme") || "light";
  applyTheme(savedTheme);

  themeButtons.forEach(function(button) {
    button.addEventListener("click", function() {
      applyTheme(button.dataset.theme);
    });
  });
}

function setTimeTheme() {
  const hour = new Date().getHours();

  document.body.classList.remove(
    "time-dawn",
    "time-day",
    "time-evening",
    "time-night"
  );

  if (hour >= 5 && hour < 11) {
    document.body.classList.add("time-dawn");
  } else if (hour >= 11 && hour < 17) {
    document.body.classList.add("time-day");
  } else if (hour >= 17 && hour < 21) {
    document.body.classList.add("time-evening");
  } else {
    document.body.classList.add("time-night");
  }
}

function resetWeatherTheme() {
  document.body.classList.remove(
    "weather-clear",
    "weather-clouds",
    "weather-rain",
    "weather-snow",
    "weather-thunder",
    "weather-mist"
  );

  const oldEffect = document.querySelector(".weather-effect-layer");
  if (oldEffect) oldEffect.remove();
}

function createWeatherParticles(type) {
  const layer = document.createElement("div");
  layer.className = "weather-effect-layer " + type;

  const count = type === "snow-effect" ? 42 : 34;

  for (let i = 0; i < count; i++) {
    const particle = document.createElement("span");
    particle.style.left = Math.random() * 100 + "%";
    particle.style.animationDelay = Math.random() * 4 + "s";
    particle.style.animationDuration = 3 + Math.random() * 5 + "s";
    particle.style.opacity = 0.35 + Math.random() * 0.55;
    layer.appendChild(particle);
  }

  document.body.appendChild(layer);
}

function applyWeatherTheme(main) {
  resetWeatherTheme();

  switch (main) {
    case "Clear":
      document.body.classList.add("weather-clear");
      break;
    case "Clouds":
      document.body.classList.add("weather-clouds");
      break;
    case "Rain":
    case "Drizzle":
      document.body.classList.add("weather-rain");
      createWeatherParticles("rain-effect");
      break;
    case "Snow":
      document.body.classList.add("weather-snow");
      createWeatherParticles("snow-effect");
      break;
    case "Thunderstorm":
      document.body.classList.add("weather-thunder");
      createWeatherParticles("rain-effect");
      break;
    case "Mist":
    case "Fog":
    case "Haze":
      document.body.classList.add("weather-mist");
      break;
    default:
      document.body.classList.add("weather-clouds");
  }
}

function getWeatherEmoji(main) {
  const icons = {
    Clear: "☀️",
    Clouds: "☁️",
    Rain: "🌧️",
    Drizzle: "🌦️",
    Snow: "❄️",
    Thunderstorm: "⛈️",
    Mist: "🌫️",
    Fog: "🌫️",
    Haze: "🌫️"
  };

  return icons[main] || "🌸";
}

function initWeather() {
  setTimeTheme();

  if (!weatherBox) return;

  if (!OPENWEATHER_KEY || OPENWEATHER_KEY === "여기에_발급받은_API_KEY") {
    weatherBox.innerHTML =
      "<p class='api-fallback'>API 키를 넣으면 날씨가 표시됩니다.</p>";
    return;
  }

  fetch(
    "https://api.openweathermap.org/data/2.5/weather?q=" +
    WEATHER_CITY +
    "&appid=" +
    OPENWEATHER_KEY +
    "&units=metric&lang=kr"
  )
    .then(function(res) {
      if (!res.ok) throw new Error("weather api error");
      return res.json();
    })
    .then(function(data) {
      const temp = Math.round(data.main.temp);
      const desc = data.weather[0].description;
      const main = data.weather[0].main;
      const icon = data.weather[0].icon;
      const emoji = getWeatherEmoji(main);

      applyWeatherTheme(main);

      weatherBox.innerHTML =
        "<div class='weather-current pretty-weather'>" +
          "<div class='weather-icon-wrap'>" +
            "<img src='https://openweathermap.org/img/wn/" + icon + "@2x.png' alt='" + desc + "'>" +
            "<b>" + emoji + "</b>" +
          "</div>" +
          "<div>" +
            "<span class='api-label'>" + WEATHER_CITY + " Weather</span>" +
            "<strong>" + temp + "°C</strong>" +
            "<p>" + desc + "</p>" +
          "</div>" +
        "</div>";
    })
    .catch(function() {
      weatherBox.innerHTML =
        "<p class='api-fallback'>날씨 정보를 불러올 수 없습니다.</p>";
    });
}

initTheme();
initWeather();

const bookBox = document.getElementById("bookBox");

function initBook() {
  if (!bookBox) return;

  fetch("https://www.googleapis.com/books/v1/volumes?q=time%20management&maxResults=1")
    .then(function(res) {
      if (!res.ok) throw new Error("books api error");
      return res.json();
    })
    .then(function(data) {
      if (!data.items || !data.items[0]) {
        throw new Error("book not found");
      }

      const book = data.items[0].volumeInfo;
      const title = book.title || "추천 도서";
      const authors = book.authors ? book.authors.join(", ") : "저자 정보 없음";
      const thumb = book.imageLinks
        ? book.imageLinks.thumbnail.replace("http://", "https://")
        : "";

      bookBox.innerHTML =
        "<div class='book-current'>" +
          (thumb
            ? "<img src='" + thumb + "' alt='" + title + "'>"
            : "<div class='book-cover-placeholder'>BOOK</div>"
          ) +
          "<div>" +
            "<span class='api-label'>Book Recommend</span>" +
            "<strong>" + title + "</strong>" +
            "<p>" + authors + "</p>" +
          "</div>" +
        "</div>";
    })
    .catch(function() {
      bookBox.innerHTML =
        "<p class='api-fallback'>추천 도서를 불러올 수 없습니다.</p>";
    });
}

initBook();
</script>

</body>
</html>

<?php
if ($isLogin && !$needsTodoMemberColumn) {
  $stmtToday->close();
  $stmtWeek->close();
  $stmtTotal->close();
  $stmtMonth->close();
}

$conn->close();
?>
