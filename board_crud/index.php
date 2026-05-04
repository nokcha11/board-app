<?php
session_start();
require_once "dbcon.php";

/* 최신 게시글 3개 가져오기 */
$sql = "SELECT idx, title, writer, reg_date, hit
        FROM tb_board
        ORDER BY idx DESC
        LIMIT 3";

$stmt = $pdo->prepare($sql);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>메인페이지</title>

<link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- 상단 -->
<?php include "header.php"; ?>

<!-- ================= 중단 ================= -->
<main>
    <div class="container">
    
        <!-- 좌측 게시판 -->
        <div class="board">
            <h2>게시판 목록</h2>

            <table>
                <tr>
                    <th>순번</th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>등록일</th>
                    <th>조회수</th>
                </tr>

                <?php
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($rows) > 0) {
                    foreach ($rows as $row) {
                ?>
                    <tr>
                        <td><?php echo $row['idx']; ?></td>
                        <td>
                            <a href="board_view.php?idx=<?php echo $row['idx']; ?>" style="color:#cfe6ff; text-decoration:none;">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($row['writer']); ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo $row['hit']; ?></td>
                    </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="5">등록된 게시글이 없습니다.</td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <!-- 우측 이미지 -->
        <div class="side">
            <img src="images/background.jpg" alt="대표이미지">

            <p>
                이 사이트는 게시판 기반 웹 프로젝트입니다.<br>
                회원 기능과 게시판 기능을 제공합니다.
            </p>
        </div>

    </div>
</main>

<!-- ================= 하단 ================= -->
<footer>
    © 2026 MY WEBSITE | All Rights Reserved | Contact: admin@site.com
</footer>

</body>
</html>