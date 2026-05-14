<?php
require_once "dbcon.php";

mysqli_set_charset($conn, "utf8mb4");

$table = "tb_a";

$allowed_tables = [
    "tb_a" => "테이블1",
    "tb_b" => "테이블2",
    "tb_c" => "테이블3"
];

if (isset($_GET['table'])) {
    if (array_key_exists($_GET['table'], $allowed_tables)) {
        $table = $_GET['table'];
    }
}

if (isset($_POST['table'])) {
    if (array_key_exists($_POST['table'], $allowed_tables)) {
        $table = $_POST['table'];
    }
}

/* INSERT */
if (isset($_POST['content'])) {

    $content = trim($_POST['content']);

    if ($content != "") {

        $sql = "INSERT INTO {$table} (content) VALUES (?)";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $content);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: ex14_db_study.php?table=" . $table);
        exit;
    }
}

/* SELECT */
$sql = "SELECT idx, content FROM {$table} ORDER BY idx DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("SQL 오류 : " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>테이블선택해서 내용보기</title>

<style>
body {
    font-family: Arial;
    background: #f5f5f5;
    padding: 40px;
}

.wrap {
    width: 700px;
    margin: 0 auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

select, input {
    padding: 8px;
    font-size: 16px;
}

.insert-box {
    margin-bottom: 20px;
}

button {
    padding: 8px 15px;
    cursor: pointer;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
}

th {
    background: #eee;
}

.empty {
    text-align: center;
    color: #888;
    padding: 20px;
}
</style>
</head>

<body>

<div class="wrap">

    <h2>테이블선택해서 내용보기</h2>

    <form method="post" class="insert-box">
        <strong>입력값 :</strong>

        <input type="hidden" name="table" value="<?= $table ?>">

        <input type="text" name="content" placeholder="문자열 입력">

        <button type="submit">삽입</button>
    </form>

    <p>
        <strong>테이블선택</strong>

        <select onchange="changeTable(this.value)">
            <option value="tb_a" <?= $table == "tb_a" ? "selected" : "" ?>>테이블1</option>
            <option value="tb_b" <?= $table == "tb_b" ? "selected" : "" ?>>테이블2</option>
            <option value="tb_c" <?= $table == "tb_c" ? "selected" : "" ?>>테이블3</option>
        </select>
    </p>

    <h3>선택한 테이블의 리스트입니다.</h3>

    <table>
        <tr>
            <th>순번</th>
            <th>내용</th>
        </tr>

        <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['idx'] ?></td>
                    <td><?= htmlspecialchars($row['content']) ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="2" class="empty">데이터가 없습니다.</td>
            </tr>
        <?php } ?>
    </table>

</div>

<script>
function changeTable(tableName) {
    location.href = "ex14_db_study.php?table=" + tableName;
}
</script>

</body>
</html>