<?php
session_start();

$isLoggedIn = isset($_SESSION["idx"], $_SESSION["loginid"]);
$quickQuestions = [
  ["tag" => "일정", "title" => "기간 일정은 어떻게 등록하나요?"],
  ["tag" => "테마", "title" => "다크 모드가 저장되지 않을 때는 어떻게 하나요?"],
  ["tag" => "도서", "title" => "책 검색 결과는 어디에 저장되나요?"],
];

$answers = [
  ["category" => "전체", "count" => 7],
  ["category" => "계정", "count" => 2],
  ["category" => "일정", "count" => 3],
  ["category" => "도서", "count" => 1],
  ["category" => "기타", "count" => 1],
];

$chats = [
  ["q" => "로그인하지 않아도 일정을 볼 수 있나요?", "a" => "개인 일정은 로그인 후 확인할 수 있습니다. 공지사항과 QnA는 누구나 볼 수 있습니다."],
  ["q" => "완료한 일정은 다시 되돌릴 수 있나요?", "a" => "일정 상세 화면에서 완료 상태를 수정하면 다시 진행 중으로 변경할 수 있습니다."],
  ["q" => "모바일에서도 사용할 수 있나요?", "a" => "네. 화면 크기에 맞게 카드와 목록이 한 줄로 정리되도록 구성했습니다."],
];
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QnA</title>
  <link id="header-light-theme" rel="stylesheet" href="css/header_bright_pastel_light.css">
  <link id="main-light-theme" rel="stylesheet" href="css/board_light.css">
  <link id="header-dark-theme" rel="stylesheet" href="css/header_glass_mood_light.css" disabled>
  <link id="main-dark-theme" rel="stylesheet" href="css/board_dark.css" disabled>
  <script src="js/theme-mode.js" defer></script>
</head>
<body>
  <div class="theme-bg"></div>
  <?php include "header.php"; ?>

  <main class="board-page qna-page">
    <section class="board-hero">
      <span class="board-chip">💬 MY TODO Help Chat</span>
      <h2>QnA</h2>
      <p>질문을 남기면 관리자 또는 매니저가 답변합니다.</p>
    </section>

    <div class="qna-layout">
      <section class="qna-main">
        <section class="quick-card">
          <div class="section-title">
            <h3>빠른 질문</h3>
            <span>관리자가 고정한 질문</span>
          </div>

          <div class="quick-grid">
            <?php foreach ($quickQuestions as $item) { ?>
              <article class="quick-item">
                <span><?= htmlspecialchars($item["tag"], ENT_QUOTES, "UTF-8") ?></span>
                <strong><?= htmlspecialchars($item["title"], ENT_QUOTES, "UTF-8") ?></strong>
              </article>
            <?php } ?>
          </div>
        </section>

        <section class="question-card">
          <h3>질문 남기기</h3>
          <p>댓글을 남기듯 질문을 작성하면 아래 QnA 채팅에 바로 표시됩니다.</p>

          <?php if ($isLoggedIn) { ?>
            <form class="question-form">
              <input type="text" placeholder="제목을 입력하세요">
              <textarea placeholder="궁금한 내용을 자세히 적어주세요"></textarea>
              <button type="button">질문 등록</button>
            </form>
          <?php } else { ?>
            <div class="login-message">질문 작성은 로그인 후 이용할 수 있습니다.</div>
          <?php } ?>
        </section>

        <section class="chat-card">
          <div class="section-title">
            <div>
              <h3>QnA 채팅</h3>
              <p>질문과 답변을 대화 흐름으로 확인할 수 있습니다.</p>
            </div>
            <span class="status-dot">답변 운영중</span>
          </div>

          <div class="chat-list">
            <?php foreach ($chats as $chat) { ?>
              <article class="chat-item question">
                <span>Q</span>
                <p><?= htmlspecialchars($chat["q"], ENT_QUOTES, "UTF-8") ?></p>
              </article>
              <article class="chat-item answer">
                <span>A</span>
                <p><?= htmlspecialchars($chat["a"], ENT_QUOTES, "UTF-8") ?></p>
              </article>
            <?php } ?>
          </div>
        </section>
      </section>

      <aside class="qna-side">
        <section class="side-card">
          <h3>질문 찾기</h3>
          <input type="search" placeholder="궁금한 내용을 검색해보세요">

          <div class="category-list">
            <?php foreach ($answers as $index => $answer) { ?>
              <a href="#" class="<?= $index === 0 ? "active" : "" ?>">
                <span><?= htmlspecialchars($answer["category"], ENT_QUOTES, "UTF-8") ?></span>
                <strong><?= $answer["count"] ?></strong>
              </a>
            <?php } ?>
          </div>

          <div class="help-box">
            <strong>답변을 못 찾았나요?</strong>
            <p>빠른 질문 아래 입력창을 통해 질문을 남겨주세요.</p>
          </div>
        </section>
      </aside>
    </div>
  </main>
</body>
</html>
