<?php
session_start();
require_once "dbcon.php";

// admin만 접근 가능
if (!isset($_SESSION['loginid']) || $_SESSION['loginid'] != 'admin') {
    echo "
    <script>
        alert('관리자만 접근 가능합니다.');
        location.href='index.php';
    </script>
    ";
    exit;
}

/* ===== 페이징 처리 ===== */
$list_count = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $list_count;

/* 전체 회원 수 */
$total_sql = "SELECT COUNT(*) FROM tb_member";
$total_stmt = $pdo->query($total_sql);
$total_rows = $total_stmt->fetchColumn();

$total_pages = ceil($total_rows / $list_count);
if ($total_pages < 1) $total_pages = 1;

/* 회원 목록 조회 */
$sql = "SELECT idx, id, name, email, join_date
        FROM tb_member
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

.admin-box {
    width: 100%;
    background: rgba(15, 27, 51, 0.9);
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #1f3b66;
}

.admin-box h2 {
    text-align: center;
    color: #4aa3ff;
    margin-bottom: 20px;
}

.admin-btn {
    display: inline-block;
    padding: 5px 10px;
    margin: 2px;
    border-radius: 4px;
    color: #fff;
    text-decoration: none;
    font-size: 12px;
    transition: 0.2s;
}

.admin-btn.edit {
    background: #4aa3ff;
}

.admin-btn.edit:hover {
    background: #2f8fff;
}

.admin-btn.delete {
    background: #ff4d4d;
}

.admin-btn.delete:hover {
    background: #e60000;
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

tr:hover {
    background: #162d55;
}

td a {
    color: #fff;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 4px;
}

td a:hover {
    background: #4aa3ff;
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
</style>
</head>

<body>

<?php include "header.php"; ?>

<main>
    <div class="container">
        <div class="admin-box">

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
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo $row['join_date']; ?></td>
                        <td>
                            <a href="edit.php?idx=<?php echo $row['idx']; ?>" class="admin-btn edit">수정</a>
                            <a href="delete.php?idx=<?php echo $row['idx']; ?>"
                            class="admin-btn delete"
                            onclick="return confirm('정말 삭제하시겠습니까?');">
                            삭제
                            </a>
                        </td>
                    </tr>
                <?php } ?>

                <?php if ($total_rows == 0) { ?>
                    <tr>
                        <td colspan="6">등록된 회원이 없습니다.</td>
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