<?php

// 1. DB 접속 정보
$host = "localhost";
$user = "lie8220";      // dothome 아이디
$password = "koko8220#"; // 보통 dothome은 비밀번호 있음 (본인 비번 넣기)
$dbname = "lie8220";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $user, $password);

    // 에러 출력 설정
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
} catch (PDOException $e) {
    echo "DB 연결 또는 쿼리 오류: " . $e->getMessage();
}
?>



<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>회원관리</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- 상단 -->
<header>
    <h1>MY WEBSITE</h1>
    <nav>
        <a href="index.html">메인</a>
        <a href="login.html">로그인</a>
        <a href="join.html">회원가입</a>
        <a href="board.html">게시판</a>
        <a href="admin.html">회원관리</a>
    </nav>
</header>

<!-- 중앙 -->
<main>
    <div class="board">
        <h2>회원관리 목록</h2>

        <table>
            <tr>
                <th>순번</th>
                <th>아이디</th>
                <th>이름</th>
                <th>연락처</th>
                <th>가입일</th>
            </tr>

            <?php
            // 쿼리 실행
            $sql = "SELECT * FROM tb_member";
            $stmt = $pdo->query($sql);

            // 결과 출력
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
                {

            ?>
                <tr>
                    <td><?php echo $row['idx']; ?></td>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>2026-04-01</td>
                </tr>

            <?php
            }
            ?>

        
        </table>

        <!-- 페이지 -->
        <div class="pagination">
            <a href="#">&lt;</a>
            <a href="#" class="active">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">&gt;</a>
        </div>
    </div>
</main>

<!-- 하단 -->
<footer>
    © 2026 MY WEBSITE | All Rights Reserved | Contact: admin@site.com
</footer>

</body>
</html>
?>