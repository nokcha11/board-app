<?php
require_once "dbcon.php";

/* ===== 페이징 처리 ===== */
$list_count = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $list_count;

/* 전체 게시글 수 */
$total_sql = "SELECT COUNT(*) FROM tb_board";
$total_stmt = $pdo->query($total_sql);
$total_rows = $total_stmt->fetchColumn();

$total_pages = ceil($total_rows / $list_count);
if ($total_pages < 1) $total_pages = 1;

/* 게시글 목록 조회 */
$sql = "SELECT idx, title, writer, reg_date, hit
        FROM tb_board
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
<title>게시판</title>

<link rel="stylesheet" href="css/style.css">

<style>
main {
    display: flex;
    justify-content: center;
    padding: 40px 0;
    background: url("images/pattern.png");
    background-size: cover;
    min-height: calc(100vh - 140px);
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
}

.board-box {
    width: 100%;
    background: rgba(15, 27, 51, 0.9);
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #1f3b66;
}

.board-box h2 {
    text-align: center;
    color: #4aa3ff;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #14284a;
    color: #cfe6ff;
    padding: 12px;
    font-size: 14px;
}

td {
    padding: 12px;
    border-bottom: 1px solid #1f3b66;
    text-align: center;
    font-size: 13px;
    color: #e6f1ff;
}

td.title {
    text-align: left;
    padding-left: 15px;
    width: 45%;
}

tr:hover {
    background: #162d55;
}

.pagination {
    text-align: center;
    margin-top: 25px;
}

.pagination a {
    display: inline-block;
    margin: 0 5px;
    padding: 7px 12px;
    color: #cfe6ff;
    border: 1px solid #1f3b66;
    border-radius: 4px;
    text-decoration: none;
}

.pagination a:hover,
.pagination a.active {
    background: #4aa3ff;
    color: #fff;
}

.write-btn {
    text-align: right;
    margin-bottom: 15px;
}

.write-btn a {
    color: #fff;
    background: #4aa3ff;
    padding: 8px 14px;
    border-radius: 5px;
    text-decoration: none;
}
</style>
</head>

<body>

<?php include "header.php"; ?>

<main>
    <div class="container">
        <div class="board-box">

            <h2>게시판 목록</h2>

            <?php if (isset($_SESSION['loginid'])) { ?>
                <div class="write-btn">
                    <a href="write.php">글쓰기</a>
                </div>
            <?php } ?>

            <table>
                <tr>
                    <th>순번</th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>등록일</th>
                    <th>조회수</th>
                </tr>

                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo $row['idx']; ?></td>
                        <td class="title">
                            <?php echo htmlspecialchars($row['title']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['writer']); ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo $row['hit']; ?></td>
                    </tr>
                <?php } ?>

                <?php if ($total_rows == 0) { ?>
                    <tr>
                        <td colspan="5">등록된 게시글이 없습니다.</td>
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
    </div>
</main>

<footer>
    © 2026 MY WEBSITE | All Rights Reserved | Contact: admin@site.com
</footer>

</body>
</html>