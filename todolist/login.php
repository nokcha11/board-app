<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>로그인</title>

<link rel="stylesheet" href="css/header.css">

<style>
* {
  box-sizing: border-box;
}

body {
  margin: 0;
  min-height: 100vh;
  background: linear-gradient(135deg, #ffd6d6, #ffc0c0);
  font-family: Arial, sans-serif;
}

/* ================= MAIN ================= */
main {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 70px 20px;
}

/* ================= 로그인 박스 ================= */
.login-box {
  width: 100%;
  max-width: 380px;
  background: #ffe5e5;
  padding: 45px 32px;
  border-radius: 18px;
  border: 3px solid #ff4d4d;
  box-shadow: 0 12px 30px rgba(255, 77, 77, 0.25);
}

/* 제목 */
.login-box h2 {
  text-align: center;
  color: #d60000;
  margin-bottom: 28px;
  font-size: 26px;
}

/* ================= 입력창 ================= */
.login-box input {
  width: 100%;
  padding: 13px;
  margin-bottom: 16px;
  border: 2px solid #ff9999;
  border-radius: 10px;
  background: #ffffff;
  color: #222;
  outline: none;
  font-size: 15px;
}

.login-box input:focus {
  border-color: #ff4d4d;
  box-shadow: 0 0 8px rgba(255, 77, 77, 0.35);
}

/* ================= 버튼 ================= */
.login-box button {
  width: 100%;
  padding: 13px;
  margin-top: 10px;
  border-radius: 10px;
  font-weight: bold;
  font-size: 15px;
  cursor: pointer;
  transition: 0.25s;
}

/* 로그인 버튼 */
.login-btn {
  background: #ff4d4d;
  border: none;
  color: #fff;
  box-shadow: 0 5px 12px rgba(255, 77, 77, 0.35);
}

.login-btn:hover {
  background: #e60000;
  transform: translateY(-2px);
}

/* 회원가입 버튼 */
.join-btn {
  background: #ffffff;
  border: 2px solid #ff4d4d;
  color: #ff3333;
}

.join-btn:hover {
  background: #ff4d4d;
  color: #fff;
  transform: translateY(-2px);
}

/* ================= 하단 ================= */
footer {
  text-align: center;
  padding: 20px;
  color: #ff3333;
  font-weight: bold;
}
</style>

</head>

<body>

<?php include "header.php"; ?>

<main>
  <div class="login-box">

    <h2>로그인</h2>

    <form method="post" action="login_ok.php">
      <input type="text" name="id" placeholder="아이디">
      <input type="password" name="pw" placeholder="패스워드">

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