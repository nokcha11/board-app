<?php
session_start();

$notices = [
  [
    "no" => 5,
    "title" => "MY TODO 이용 안내",
    "summary" => "캘린더 일정 등록, 완료 체크, 주간 일정 확인 기능을 한 화면에서 사용할 수 있습니다.",
    "type" => "중요공지",
    "writer" => "관리자",
    "date" => "2026.05.27",
    "views" => 42,
  ],
  [
    "no" => 4,
    "title" => "라이트 / 다크 모드 적용 안내",
    "summary" => "상단 테마 버튼으로 원하는 분위기의 화면을 선택할 수 있습니다.",
    "type" => "일반공지",
    "writer" => "관리자",
    "date" => "2026.05.26",
    "views" => 31,
  ],
  [
    "no" => 3,
    "title" => "도서 검색 위젯 업데이트",
    "summary" => "메인 화면에서 관심 있는 책을 검색하고 선택할 수 있도록 기능을 정리했습니다.",
    "type" => "업데이트",
    "writer" => "관리자",
    "date" => "2026.05.24",
    "views" => 27,
  ],
  [
    "no" => 2,
    "title" => "일정 기간 등록 기능 안내",
    "summary" => "시작일과 종료일을 지정해 여러 날에 걸친 할 일을 등록할 수 있습니다.",
    "type" => "일반공지",
    "writer" => "관리자",
    "date" => "2026.05.22",
    "views" => 18,
  ],
  [
    "no" => 1,
    "title" => "MY TODO 공지사항 오픈",
    "summary" => "서비스 변경 사항과 중요한 안내를 이곳에서 확인할 수 있습니다.",
    "type" => "일반공지",
    "writer" => "관리자",
    "date" => "2026.05.20",
    "views" => 15,
  ],
];

$importantCount = count(array_filter($notices, function ($notice) {
  return $notice["type"] === "중요공지";
}));
$monthCount = count(array_filter($notices, function ($notice) {
  return strpos($notice["date"], date("Y.m")) === 0;
}));
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>공지사항</title>
  <link id="header-light-theme" rel="stylesheet" href="css/header_bright_pastel_light.css">
  <link id="main-light-theme" rel="stylesheet" href="css/board_light.css">
  <link id="header-dark-theme" rel="stylesheet" href="css/header_glass_mood_light.css" disabled>
  <link id="main-dark-theme" rel="stylesheet" href="css/board_dark.css" disabled>
  <script src="js/theme-mode.js" defer></script>
</head>
<body>
  <div class="theme-bg"></div>
  <?php include "header.php"; ?>

  <main class="board-page">
    <section class="board-hero">
      <span class="board-chip">📢 MY TODO Notice</span>
      <h2>공지사항</h2>
      <p>중요한 소식과 업데이트를 확인할 수 있습니다.</p>
    </section>

    <section class="notice-stats" aria-label="공지 요약">
      <article>
        <span>전체 공지</span>
        <strong><?= count($notices) ?></strong>
      </article>
      <article>
        <span>중요 공지</span>
        <strong><?= $importantCount ?></strong>
      </article>
      <article>
        <span>이번 달 등록</span>
        <strong><?= $monthCount ?></strong>
      </article>
    </section>

    <section class="board-panel">
      <div class="board-tools">
        <label class="search-field">
          <span class="sr-only">공지 검색</span>
          <input type="search" placeholder="제목 또는 내용 검색">
        </label>
        <select aria-label="공지 분류">
          <option>전체 분류</option>
          <option>중요공지</option>
          <option>일반공지</option>
          <option>업데이트</option>
        </select>
        <button type="button" class="icon-btn" aria-label="검색">⌕</button>
        <a href="#" class="write-btn">공지 작성</a>
      </div>

      <div class="notice-table" role="table" aria-label="공지사항 목록">
        <div class="notice-row notice-head" role="row">
          <span role="columnheader">번호</span>
          <span role="columnheader">제목</span>
          <span role="columnheader">분류</span>
          <span role="columnheader">작성자</span>
          <span role="columnheader">작성일</span>
          <span role="columnheader">조회수</span>
        </div>

        <?php foreach ($notices as $notice) { ?>
          <article class="notice-row" role="row">
            <span class="notice-no" role="cell"><?= $notice["no"] ?></span>
            <div class="notice-title" role="cell">
              <strong><?= htmlspecialchars($notice["title"], ENT_QUOTES, "UTF-8") ?></strong>
              <p><?= htmlspecialchars($notice["summary"], ENT_QUOTES, "UTF-8") ?></p>
            </div>
            <span class="badge <?= $notice["type"] === "중요공지" ? "important" : "" ?>" role="cell">
              <?= htmlspecialchars($notice["type"], ENT_QUOTES, "UTF-8") ?>
            </span>
            <span role="cell"><?= htmlspecialchars($notice["writer"], ENT_QUOTES, "UTF-8") ?></span>
            <span role="cell"><?= htmlspecialchars($notice["date"], ENT_QUOTES, "UTF-8") ?></span>
            <span role="cell"><?= $notice["views"] ?></span>
          </article>
        <?php } ?>
      </div>

      <div class="board-footer">
        <span>총 <?= count($notices) ?>건 중 1-<?= count($notices) ?>건 표시</span>
        <div class="pagination" aria-label="페이지 이동">
          <a href="#" aria-label="이전 페이지">&lt;</a>
          <a href="#" class="active">1</a>
          <a href="#">2</a>
          <a href="#" aria-label="다음 페이지">&gt;</a>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
