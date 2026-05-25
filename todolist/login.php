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

  body {
    margin: 0;
    min-height: 100vh;
    color: #412531;
    font-family: Arial, sans-serif;
    background:
      radial-gradient(circle at top left, rgba(255, 238, 244, 0.95), transparent 34%),
      linear-gradient(135deg, #ffe4eb 0%, #ffd1dc 45%, #fff8fb 100%);
  }

  main {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 58px 20px;
  }

  .login-box {
    width: min(380px, 100%);
    padding: 40px 30px;
    background: rgba(255, 255, 255, 0.56);
    border: 1px solid rgba(255, 203, 218, 0.76);
    border-radius: 18px;
    box-shadow: 0 14px 34px rgba(204, 82, 111, 0.14);
    backdrop-filter: blur(18px) saturate(140%);
    -webkit-backdrop-filter: blur(18px) saturate(140%);
  }

  .login-box h2 {
    text-align: center;
    color: #a9345b;
    margin: 0 0 28px;
    font-size: 26px;
  }

  .login-box input {
    width: 100%;
    min-height: 48px;
    padding: 12px 14px;
    margin-bottom: 14px;
    border: 1px solid rgba(255, 183, 203, 0.9);
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.78);
    color: #412531;
    outline: none;
    font-size: 15px;
  }

  .login-box input:focus {
    border-color: #df5b81;
    box-shadow: 0 0 0 3px rgba(223, 91, 129, 0.14);
  }

  .login-box button {
    width: 100%;
    min-height: 44px;
    padding: 12px;
    margin-top: 10px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
  }

  .login-btn {
    background: #df5b81;
    border: none;
    color: #fff;
  }

  .join-btn {
    background: rgba(255, 255, 255, 0.72);
    border: 1px solid rgba(255, 179, 199, 0.88);
    color: #bd3d65;
  }

  footer {
    text-align: center;
    padding: 20px;
    color: #a9345b;
    font-weight: 700;
  }

  @media (prefers-color-scheme: dark) {
    body {
      color: #ffe6ef;
      background:
        radial-gradient(circle at top left, rgba(124, 61, 93, 0.42), transparent 34%),
        linear-gradient(135deg, #25172d 0%, #3a213b 48%, #513046 100%);
    }

    .login-box {
      background: rgba(56, 31, 55, 0.58);
      border-color: rgba(255, 199, 219, 0.18);
    }

    .login-box h2,
    footer {
      color: #ffd1df;
    }

    .login-box input {
      color: #ffe6ef;
      background: rgba(255, 214, 229, 0.08);
      border-color: rgba(255, 214, 229, 0.16);
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
