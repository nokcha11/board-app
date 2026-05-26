<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>로그인</title>
  <link rel="stylesheet" href="css/header.css">

  <style>
    * {
      box-sizing: border-box;
    }

    html,
    body {
      margin: 0;
      min-height: 100%;
    }

    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: #412531;
      font-family: Arial, sans-serif;
      overflow-x: hidden;

      background:
        linear-gradient(
          rgba(255, 245, 250, 0.42),
          rgba(255, 235, 244, 0.62)
        ),
        url("images/cover1.jpg") center / cover fixed no-repeat;
    }

    body::before,
    body::after {
      content: "";
      position: fixed;
      border-radius: 50%;
      pointer-events: none;
      z-index: -1;
    }

    body::before {
      width: 520px;
      height: 520px;
      right: -150px;
      top: -140px;
      background: rgba(255, 150, 190, 0.26);
      filter: blur(120px);
    }

    body::after {
      width: 460px;
      height: 460px;
      left: -130px;
      bottom: -150px;
      background: rgba(255, 225, 240, 0.42);
      filter: blur(110px);
    }

    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 70px 20px;
    }

    .login-box {
      width: min(390px, 100%);
      padding: 44px 34px;
      border-radius: 30px;

      background: rgba(255, 255, 255, 0.07);
      border: 1px solid rgba(255, 255, 255, 0.28);

      backdrop-filter: blur(18px) saturate(150%);
      -webkit-backdrop-filter: blur(18px) saturate(150%);

      box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.22),
        0 20px 50px rgba(120, 50, 80, 0.10);
    }

    .login-box h2 {
      text-align: center;
      color: #a9345b;
      margin: 0 0 28px;
      font-size: 28px;
      font-weight: 900;
    }

    .login-box input {
      width: 100%;
      min-height: 50px;
      padding: 12px 16px;
      margin-bottom: 14px;

      border: 1px solid rgba(255, 255, 255, 0.34);
      border-radius: 14px;

      background: rgba(255, 255, 255, 0.16);
      color: #412531;

      outline: none;
      font-size: 15px;

      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }

    .login-box input::placeholder {
      color: rgba(85, 55, 70, 0.56);
      font-weight: 700;
    }

    .login-box input:focus {
      border-color: rgba(255, 120, 168, 0.75);
      box-shadow: 0 0 0 4px rgba(255, 120, 168, 0.16);
    }

    .login-box button {
      width: 100%;
      min-height: 46px;
      padding: 12px;
      margin-top: 10px;
      border-radius: 999px;
      font-weight: 800;
      font-size: 15px;
      cursor: pointer;
      transition: 0.25s;
    }

    .login-btn {
      background: linear-gradient(135deg, #ff8eb6, #f45f98);
      border: none;
      color: #fff;
      box-shadow: 0 12px 28px rgba(244, 95, 152, 0.25);
    }

    .join-btn {
      background: rgba(255, 255, 255, 0.10);
      border: 1px solid rgba(255, 255, 255, 0.30);
      color: #bd3d65;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }

    .login-box button:hover {
      transform: translateY(-2px);
    }

    footer {
      margin-top: auto;
      text-align: center;
      padding: 24px 20px;
      color: #a9345b;
      font-weight: 800;
    }

    /* =========================
       DARK MODE
    ========================= */

    body.dark-mode {
      color: #ffe6ef;
      background:
        linear-gradient(
          rgba(28, 18, 28, 0.70),
          rgba(28, 18, 28, 0.84)
        ),
        url("images/cover1.jpg") center / cover fixed no-repeat;
    }

    body.dark-mode::before {
      background: rgba(255, 120, 180, 0.18);
    }

    body.dark-mode::after {
      background: rgba(130, 80, 145, 0.22);
    }

    body.dark-mode .login-box {
      background: rgba(255, 214, 229, 0.08);
      border-color: rgba(255, 221, 235, 0.22);
      box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.14),
        0 22px 54px rgba(9, 4, 18, 0.34);
    }

    body.dark-mode .login-box h2,
    body.dark-mode footer {
      color: #ffd1df;
    }

    body.dark-mode .login-box input {
      color: #ffe6ef;
      background: rgba(255, 235, 244, 0.10);
      border-color: rgba(255, 226, 238, 0.20);
    }

    body.dark-mode .login-box input::placeholder {
      color: rgba(255, 230, 240, 0.58);
    }

    body.dark-mode .join-btn {
      color: #ffd8e5;
      background: rgba(255, 235, 244, 0.10);
      border-color: rgba(255, 226, 238, 0.24);
    }

    @media (prefers-color-scheme: dark) {
      body:not(.light-mode) {
        color: #ffe6ef;
        background:
          linear-gradient(
            rgba(28, 18, 28, 0.70),
            rgba(28, 18, 28, 0.84)
          ),
          url("images/cover1.jpg") center / cover fixed no-repeat;
      }

      body:not(.light-mode) .login-box {
        background: rgba(255, 214, 229, 0.08);
        border-color: rgba(255, 221, 235, 0.22);
      }

      body:not(.light-mode) .login-box h2,
      body:not(.light-mode) footer {
        color: #ffd1df;
      }

      body:not(.light-mode) .login-box input {
        color: #ffe6ef;
        background: rgba(255, 235, 244, 0.10);
        border-color: rgba(255, 226, 238, 0.20);
      }

      body:not(.light-mode) .join-btn {
        color: #ffd8e5;
        background: rgba(255, 235, 244, 0.10);
        border-color: rgba(255, 226, 238, 0.24);
      }
    }

    @media (max-width: 620px) {
      main {
        padding: 48px 16px;
      }

      .login-box {
        padding: 36px 24px;
        border-radius: 24px;
      }
    }
  </style>
</head>

<body>
<?php include "header.php"; ?>

<main>
  <div class="login-box">
    <h2>로그인</h2>

    <form method="post" action="login_ok.php">
      <input type="text" name="id" placeholder="아이디" required>
      <input type="password" name="pw" placeholder="비밀번호" required>

      <button type="submit" class="login-btn">로그인</button>
      <button type="button" onclick="location.href='join.php'" class="join-btn">회원가입</button>
    </form>
  </div>
</main>

<footer>
  © 2026 MY TODO | All Rights Reserved
</footer>
</body>
</html>