<?php
session_start();
?>

<header>
    
    <h1>MY WEBSITE</h1>
      <!-- 👇 환영문구 추가 -->
    <?php if (isset($_SESSION['loginid'])) { ?>
        <div class="welcome-header">
            <?php echo $_SESSION['loginid']; ?>님 반갑습니다!
        </div>
    <?php } ?>

    <nav>
        <a href="index.php">메인</a>

        <?php if (!isset($_SESSION['loginid'])) { ?>
            <a href="login.php">로그인</a>
        <?php } else { ?>
            <a href="logout.php">로그아웃</a>
        <?php } ?>

        <a href="myinfo.php">내정보</a>
        <a href="board.php">게시판</a>

        <?php if (isset($_SESSION['loginid']) && $_SESSION['loginid'] == 'admin') { ?>
            <!-- [admin]관리자만 보임 -->
        <a href="admin.php">회원관리</a>
        <?php } ?>
    </nav>
</header>