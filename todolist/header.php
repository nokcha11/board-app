<?php
session_start();
?>

<link rel="stylesheet" href="css/header.css">

<header class="main-header">

    <div class="header-top">
        <h1>MY TODO</h1>

        <!-- 환영문구 -->
        <?php if (isset($_SESSION['loginid'])) { ?>
            <div class="welcome-header">
                <?php echo $_SESSION['loginid']; ?>님 반갑습니다!
            </div>
        <?php } ?>
    </div>

    <nav>
        <a href="index.php">메인</a>

        <?php if (!isset($_SESSION['loginid'])) { ?>
            <a href="login.php">로그인</a>
        <?php } else { ?>
            <a href="logout.php">로그아웃</a>
        <?php } ?>

        <a href="myinfo.php">내정보</a>
        <a href="ToDo_list.php">월별계획표</a>

        <?php if (isset($_SESSION['loginid']) && $_SESSION['loginid'] == 'admin') { ?>
            <a href="admin.php">회원관리</a>
        <?php } ?>
    </nav>

</header>

<script>
document.querySelectorAll("nav a").forEach(link => {
  link.addEventListener("mouseenter", function(e) {
    const colors = ["#fff176", "#ff80ab", "#80d8ff", "#b388ff", "#ffffff", "#ffb74d"];

    for (let i = 0; i < 8; i++) {
      const star = document.createElement("div");
      star.className = "star";

      const x = (Math.random() * 70 - 35) + "px";
      const y = (Math.random() * 55 - 45) + "px";
      const color = colors[Math.floor(Math.random() * colors.length)];

      star.style.setProperty("--x", x);
      star.style.setProperty("--y", y);
      star.style.left = e.pageX + "px";
      star.style.top = (e.pageY + 12) + "px";
      star.style.color = color;

      document.body.appendChild(star);

      setTimeout(() => {
        star.remove();
      }, 750);
    }
  });
});
</script>