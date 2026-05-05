<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>회원가입</title>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/seasonEffect.css">
<script src="js/seasonEffect.js" defer></script>

<style>
* {
  box-sizing: border-box;
}

html,
body {
  margin: 0;
  padding: 0;
  min-height: 100vh;
}

body {
  background: #ffc9c9;
  font-family: Arial, sans-serif;

  display: flex;
  flex-direction: column;
}

/* 헤더는 무조건 상단 */
header {
  flex-shrink: 0;
  width: 100%;
}

/* 가운데 영역 */
.join-main {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 20px;
}

/* 회원가입 박스 */
.join-box {
  width: 100%;
  max-width: 420px;
  background: #ffe1e1;
  border: 2px solid #ff4d4d;
  border-radius: 18px;
  padding: 42px 34px;
  box-shadow: 0 8px 24px rgba(255, 77, 77, 0.25);
}

.join-box h2 {
  text-align: center;
  color: #e60000;
  margin: 0 0 28px;
  font-size: 26px;
}

.join-box input {
  width: 100%;
  padding: 14px 15px;
  margin-bottom: 14px;
  border: 2px solid #ff9f9f;
  border-radius: 10px;
  background: #fffafa;
  color: #333;
  outline: none;
  font-size: 15px;
}

.join-box input::placeholder {
  color: #999;
}

.join-box input:focus {
  border-color: #ff4d4d;
  box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.15);
}

.join-box button {
  width: 100%;
  padding: 14px;
  margin-top: 8px;
  border: none;
  border-radius: 10px;
  background: #ff4d4d;
  color: white;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
}

.join-box button:hover {
  background: #ff3333;
}

.login-link {
  margin-top: 18px;
  text-align: center;
  font-size: 14px;
  color: #777;
}

.login-link a {
  color: #ff3333;
  font-weight: bold;
  text-decoration: none;
}

/* 푸터는 아래 */
footer {
  flex-shrink: 0;
  text-align: center;
  color: #ff3333;
  font-weight: bold;
  padding: 18px 20px;
}

@media (max-width: 768px) {
  .join-main {
    padding: 30px 16px;
  }

  .join-box {
    max-width: 100%;
    padding: 34px 24px;
  }
}
</style>
</head>

<body>

<?php include "header.php"; ?>

<main class="join-main">
  <div class="join-box">
    <h2>회원가입</h2>

    <form method="post" action="join_ok.php" onsubmit="return checkForm()">
      <input type="text" placeholder="아이디 입력" name="id" required>
      <input type="password" placeholder="패스워드" name="pw" id="pw" required>
      <input type="password" placeholder="패스워드 확인" name="pw2" id="pw2" required>
      <input type="text" placeholder="이름" name="name" required>
      <input type="email" placeholder="이메일 입력" name="email" required>

      <button type="submit">회원가입</button>
    </form>

    <div class="login-link">
      이미 계정이 있으신가요? <a href="login.php">로그인</a>
    </div>
  </div>
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