<?php

// 1. DB 접속 정보
$host = "localhost";
$user = "lie8220";      // dothome 아이디
$password = "koko8220#"; // 보통 dothome은 비밀번호 있음 (본인 비번 넣기)
$dbname = "lie8220";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "DB 연결 또는 쿼리 오류: " . $e->getMessage();
    exit;
}

/* ===== 페이징 처리 ===== */
$list_count = 8;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $list_count;

// 전체 데이터 개수
$total_sql = "SELECT COUNT(*) FROM tb_member";
$total_stmt = $pdo->query($total_sql);
$total_rows = $total_stmt->fetchColumn();

$total_pages = ceil($total_rows / $list_count);
if ($total_pages < 1) $total_pages = 1;

// 데이터 조회 (최신순)
$sql = "SELECT * FROM tb_member
        ORDER BY idx DESC
        LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $list_count, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>회원관리</title>
<link rel="stylesheet" href="css/style.css">
<style>
.board td a {
    color: #fff;
    text-decoration: none; 
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
}

.board td a:hover {
    background: #4da3ff;
    color: #fff;
}
</style>
</head>
<body>

<header>
    <h1>MY WEBSITE</h1>
    <nav>
        <a href="index.html">메인</a>
        <a href="login.html">로그인</a>
        <a href="join.html">회원가입</a>
        <a href="board.html">게시판</a>
        <a href="admin.php">회원관리</a>
    </nav>
</header>

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
                <th>관리</th>
            </tr>

            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo $row['idx']; ?></td>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['join_date']; ?></td>
                <td>
                    <a href="edit.php?idx=<?php echo $row['idx']; ?>">수정</a> |
                    <a href="delete.php?idx=<?php echo $row['idx']; ?>"
                       onclick="return confirm('정말 삭제하시겠습니까?');">
                       삭제
                    </a>
                </td>
            </tr>
            <?php } ?>
        </table>

        <div class="pagination">
            <?php if ($page > 1) { ?>
                <a href="?page=<?php echo $page - 1; ?>">«</a>
            <?php } ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php } ?>

            <?php if ($page < $total_pages) { ?>
                <a href="?page=<?php echo $page + 1; ?>">»</a>
            <?php } ?>
        </div>
    </div>
</main>

<footer>
    © 2026 MY WEBSITE | All Rights Reserved | Contact: admin@site.com
</footer>

</body>
</html>