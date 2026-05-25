<?php
$isLoggedIn = isset($_SESSION['idx'], $_SESSION['loginid']);
$loginId = $isLoggedIn ? (string)$_SESSION['loginid'] : '';
$isAdmin = $isLoggedIn && $loginId === 'admin';
?>

<link rel="stylesheet" href="css/header.css">

<header class="main-header">
  <div class="header-top">
    <h1>MY TODO</h1>

    <?php if ($isLoggedIn) { ?>
      <div class="welcome-header">
        <?= htmlspecialchars($loginId, ENT_QUOTES, 'UTF-8') ?>님, 반갑습니다
      </div>
    <?php } ?>
  </div>

  <nav>
    <a href="index.php">메인</a>

    <?php if ($isLoggedIn) { ?>
      <a href="ToDo_list.php">월별계획표</a>
      <?php if ($isAdmin) { ?>
        <a href="admin.php">회원관리</a>
      <?php } else { ?>
        <a href="member_info.php">나의 정보</a>
      <?php } ?>
      <a href="logout.php">로그아웃</a>
    <?php } else { ?>
      <a href="login.php">로그인</a>
      <a href="join.php">회원가입</a>
      <a href="ToDo_list.php">월별계획표</a>
    <?php } ?>
  </nav>
</header>

<script>
(function() {
  function applySavedTheme() {
    const savedTheme = localStorage.getItem("todoTheme");
    document.body.classList.toggle("dark-mode", savedTheme === "dark");
    document.body.classList.toggle("light-mode", savedTheme === "light");
  }

  if (document.body) {
    applySavedTheme();
  } else {
    document.addEventListener("DOMContentLoaded", applySavedTheme);
  }
})();

document.querySelectorAll("nav a").forEach(function(link) {
  link.addEventListener("mouseenter", function(e) {
    const colors = ["#fff5f8", "#ff9fbd", "#ffd1dc", "#f7b2c4", "#ffffff", "#ffb8c6"];

    for (let i = 0; i < 8; i++) {
      const star = document.createElement("div");
      star.className = "star";
      star.style.setProperty("--x", (Math.random() * 70 - 35) + "px");
      star.style.setProperty("--y", (Math.random() * 55 - 45) + "px");
      star.style.left = e.pageX + "px";
      star.style.top = (e.pageY + 12) + "px";
      star.style.color = colors[Math.floor(Math.random() * colors.length)];
      document.body.appendChild(star);
      setTimeout(function() {
        star.remove();
      }, 750);
    }
  });
});
</script>
