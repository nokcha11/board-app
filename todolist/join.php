<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>회원가입</title>
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/seasonEffect.css">
  <script src="js/seasonEffect.js" defer></script>
  <style>
  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    min-height: 100vh;
    color: #472536;
    font-family: Arial, sans-serif;
    background:
      radial-gradient(circle at 14% 18%, rgba(255, 255, 255, 0.74), transparent 24%),
      radial-gradient(circle at 88% 10%, rgba(255, 180, 205, 0.42), transparent 28%),
      linear-gradient(135deg, #ffe4ec 0%, #ffd1dc 42%, #fff8fb 100%);
  }

  .join-main {
    width: min(1180px, calc(100% - 32px));
    min-height: calc(100vh - 92px);
    margin: 0 auto;
    padding: 54px 0 70px;
    display: flex;
    align-items: center;
  }

  .join-shell {
    width: 100%;
    display: grid;
    grid-template-columns: minmax(0, 1.18fr) minmax(360px, 0.82fr);
    gap: 22px;
    align-items: stretch;
  }

  .intro-panel,
  .join-box,
  .intro-card {
    background: rgba(255, 255, 255, 0.52);
    border: 1px solid rgba(255, 203, 218, 0.76);
    box-shadow:
      0 18px 44px rgba(204, 82, 111, 0.15),
      inset 0 1px 0 rgba(255, 255, 255, 0.24);
    backdrop-filter: blur(22px) saturate(150%);
    -webkit-backdrop-filter: blur(22px) saturate(150%);
  }

  .intro-panel,
  .join-box {
    border-radius: 24px;
    padding: clamp(28px, 4vw, 46px);
  }

  .intro-panel {
    position: relative;
    overflow: hidden;
    min-height: 620px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .intro-panel::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
      radial-gradient(circle at 12% 10%, rgba(255, 130, 170, 0.2), transparent 26%),
      radial-gradient(circle at 76% 72%, rgba(255, 255, 255, 0.34), transparent 24%);
    pointer-events: none;
  }

  .intro-content,
  .intro-cards {
    position: relative;
    z-index: 1;
  }

  .intro-badge {
    display: inline-flex;
    width: fit-content;
    align-items: center;
    min-height: 34px;
    margin-bottom: 26px;
    padding: 8px 14px;
    color: #a9345b;
    background: rgba(255, 255, 255, 0.54);
    border: 1px solid rgba(255, 190, 208, 0.8);
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0;
  }

  .intro-panel h2 {
    margin: 0 0 22px;
    color: #8f254d;
    font-size: clamp(34px, 4.4vw, 56px);
    line-height: 1.12;
    letter-spacing: 0;
  }

  .intro-panel p {
    margin: 0;
    max-width: 520px;
    color: #765465;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.7;
  }

  .intro-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 28px;
  }

  .intro-tags span {
    display: inline-flex;
    min-height: 38px;
    align-items: center;
    padding: 9px 15px;
    color: #9d3157;
    background: rgba(255, 255, 255, 0.54);
    border: 1px solid rgba(255, 190, 208, 0.82);
    border-radius: 999px;
    font-size: 13px;
    font-weight: 800;
  }

  .intro-cards {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin-top: 46px;
  }

  .intro-card {
    min-height: 150px;
    padding: 20px 18px;
    border-radius: 18px;
  }

  .intro-card strong {
    display: block;
    margin-bottom: 12px;
    color: #bd3d65;
    font-size: 13px;
    font-weight: 900;
  }

  .intro-card span {
    display: block;
    color: #68475a;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.6;
  }

  .join-box {
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .join-box h2 {
    margin: 0 0 10px;
    color: #a9345b;
    font-size: 30px;
    text-align: left;
  }

  .join-lead {
    margin: 0 0 28px;
    color: #765465;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.6;
  }

  .form-field {
    margin-bottom: 14px;
  }

  .form-field label {
    display: block;
    margin-bottom: 7px;
    color: #9d3157;
    font-size: 13px;
    font-weight: 800;
  }

  .join-box input {
    width: 100%;
    min-height: 48px;
    padding: 12px 14px;
    border: 1px solid rgba(255, 183, 203, 0.9);
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.72);
    color: #412531;
    outline: none;
    font-size: 15px;
    font-weight: 700;
  }

  .join-box input::placeholder {
    color: rgba(118, 84, 101, 0.62);
  }

  .join-box input:focus {
    border-color: #df5b81;
    box-shadow: 0 0 0 3px rgba(223, 91, 129, 0.14);
  }

  .join-box button {
    width: 100%;
    min-height: 48px;
    padding: 13px;
    margin-top: 10px;
    border: none;
    border-radius: 999px;
    background: #df5b81;
    color: white;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 10px 24px rgba(204, 82, 111, 0.24);
  }

  .login-link {
    margin-top: 18px;
    text-align: center;
    font-size: 14px;
    color: #765465;
    font-weight: 700;
  }

  .login-link a {
    color: #bd3d65;
    font-weight: 900;
    text-decoration: none;
  }

  footer {
    text-align: center;
    color: #a9345b;
    font-weight: 700;
    padding: 0 20px 22px;
  }

  body.dark-mode {
    color: #ffe6ef;
    background:
      radial-gradient(circle at 18% 12%, rgba(255, 160, 198, 0.18), transparent 34%),
      radial-gradient(circle at 86% 18%, rgba(182, 109, 196, 0.14), transparent 30%),
      linear-gradient(135deg, #24152d 0%, #3a1d3a 46%, #512940 100%);
  }

  body.dark-mode .intro-panel,
  body.dark-mode .join-box,
  body.dark-mode .intro-card {
    background: rgba(255, 214, 229, 0.075);
    border-color: rgba(255, 221, 235, 0.22);
    box-shadow:
      0 18px 44px rgba(9, 4, 18, 0.32),
      inset 0 1px 0 rgba(255, 255, 255, 0.12),
      inset 0 0 24px rgba(255, 193, 217, 0.035);
    backdrop-filter: blur(24px) saturate(155%);
    -webkit-backdrop-filter: blur(24px) saturate(155%);
  }

  body.dark-mode .intro-badge,
  body.dark-mode .intro-tags span {
    color: #ffd8e5;
    background: rgba(255, 235, 244, 0.1);
    border-color: rgba(255, 226, 238, 0.22);
  }

  body.dark-mode .intro-panel h2,
  body.dark-mode .join-box h2,
  body.dark-mode footer {
    color: #ffd1df;
  }

  body.dark-mode .intro-panel p,
  body.dark-mode .intro-card span,
  body.dark-mode .join-lead,
  body.dark-mode .login-link {
    color: #e7bdcf;
  }

  body.dark-mode .intro-card strong,
  body.dark-mode .form-field label,
  body.dark-mode .login-link a {
    color: #ffbfd2;
  }

  body.dark-mode .join-box input {
    color: #ffe6ef;
    background: rgba(255, 236, 244, 0.08);
    border-color: rgba(255, 226, 238, 0.18);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
  }

  body.dark-mode .join-box input::placeholder {
    color: rgba(255, 230, 240, 0.48);
  }

  body.dark-mode .join-box button {
    color: #3a1732;
    background: rgba(255, 190, 211, 0.9);
    border: 1px solid rgba(255, 231, 239, 0.44);
  }

  @media (prefers-color-scheme: dark) {
    body:not(.light-mode) {
      color: #ffe6ef;
      background:
        radial-gradient(circle at 18% 12%, rgba(255, 160, 198, 0.18), transparent 34%),
        radial-gradient(circle at 86% 18%, rgba(182, 109, 196, 0.14), transparent 30%),
        linear-gradient(135deg, #24152d 0%, #3a1d3a 46%, #512940 100%);
    }

    body:not(.light-mode) .intro-panel,
    body:not(.light-mode) .join-box,
    body:not(.light-mode) .intro-card {
      background: rgba(255, 214, 229, 0.075);
      border-color: rgba(255, 221, 235, 0.22);
      box-shadow:
        0 18px 44px rgba(9, 4, 18, 0.32),
        inset 0 1px 0 rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(24px) saturate(155%);
      -webkit-backdrop-filter: blur(24px) saturate(155%);
    }
  }

  @media (max-width: 980px) {
    .join-main {
      align-items: flex-start;
      padding-top: 34px;
    }

    .join-shell {
      grid-template-columns: 1fr;
    }

    .intro-panel {
      min-height: auto;
    }
  }

  @media (max-width: 680px) {
    .join-main {
      width: min(100% - 24px, 1180px);
      padding: 26px 0 46px;
    }

    .intro-panel,
    .join-box {
      border-radius: 18px;
      padding: 24px 20px;
    }

    .intro-cards {
      grid-template-columns: 1fr;
      margin-top: 30px;
    }

    .intro-tags span {
      width: 100%;
      justify-content: center;
    }
  }
  </style>
</head>

<body>
<?php include "header.php"; ?>

<main class="join-main">
  <section class="join-shell">
    <div class="intro-panel">
      <div class="intro-content">
        <span class="intro-badge">MY TODO PLANNER</span>
        <h2>오늘을 기록하고,<br>루틴을 만들고,<br>나만의 하루를 완성하세요</h2>
        <p>
          일정, 목표, 감정 기록까지<br>
          하루의 흐름을 감성적으로 관리할 수 있습니다.
        </p>

        <div class="intro-tags">
          <span>오늘 일정 보기</span>
          <span>루틴 관리 시작</span>
          <span>기록 아카이브 열기</span>
        </div>
      </div>

      <div class="intro-cards">
        <div class="intro-card">
          <strong>DAILY ROUTINE</strong>
          <span>오늘의 목표와 할 일을<br>하루 흐름에 맞게 관리합니다.</span>
        </div>
        <div class="intro-card">
          <strong>MEMORY NOTE</strong>
          <span>감정과 기록을 남기며<br>나만의 아카이브를 만들어갑니다.</span>
        </div>
        <div class="intro-card">
          <strong>MONTHLY PLAN</strong>
          <span>월별 계획표와 주간 목표를<br>한눈에 정리할 수 있습니다.</span>
        </div>
      </div>
    </div>

    <div class="join-box">
      <h2>회원가입</h2>
      <p class="join-lead">MY TODO에서 나만의 기록과 계획을 시작해보세요.</p>

      <form method="post" action="join_ok.php" onsubmit="return checkForm()">
        <div class="form-field">
          <label for="id">아이디</label>
          <input type="text" placeholder="아이디 입력" name="id" id="id" required>
        </div>
        <div class="form-field">
          <label for="pw">비밀번호</label>
          <input type="password" placeholder="비밀번호" name="pw" id="pw" required>
        </div>
        <div class="form-field">
          <label for="pw2">비밀번호 확인</label>
          <input type="password" placeholder="비밀번호 확인" name="pw2" id="pw2" required>
        </div>
        <div class="form-field">
          <label for="name">이름</label>
          <input type="text" placeholder="이름" name="name" id="name" required>
        </div>
        <div class="form-field">
          <label for="email">이메일</label>
          <input type="email" placeholder="이메일 입력" name="email" id="email" required>
        </div>

        <button type="submit">회원가입</button>
      </form>

      <div class="login-link">
        이미 계정이 있으신가요? <a href="login.php">로그인</a>
      </div>
    </div>
  </section>
</main>

<footer>
  © 2026 MY TODO | All Rights Reserved
</footer>

<script>
function checkForm() {
  const pw = document.getElementById("pw").value;
  const pw2 = document.getElementById("pw2").value;

  if (pw !== pw2) {
    alert("비밀번호가 일치하지 않습니다.");
    return false;
  }

  return true;
}
</script>
</body>
</html>
