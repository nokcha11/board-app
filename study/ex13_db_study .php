<?php
require_once "dbcon_pdo.php";

/* 기본 테이블 */
$table = "tb_a";

/* 허용 테이블 */
$allowedTables = ["tb_a", "tb_b", "tb_c"];

/* GET 테이블 선택 */
if (isset($_GET['table']) && in_array($_GET['table'], $allowedTables)) {
    $table = $_GET['table'];
}

/* POST 테이블 선택 */
if (isset($_POST['table']) && in_array($_POST['table'], $allowedTables)) {
    $table = $_POST['table'];
}

/* INSERT 처리 */
if (isset($_POST['content'])) {
    $content = trim($_POST['content']);

    if ($content != "") {
        $sql = "INSERT INTO {$table} (content) VALUES (:content)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":content", $content);
        $stmt->execute();

        header("Location: ex10_db_study.php?table=" . $table);
        exit;
    }
}

/* SELECT 처리 */
$sql = "SELECT idx, content FROM {$table} ORDER BY idx DESC";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>테이블선택해서 내용보기</title>

<style>
body {
  margin: 0;
  padding: 40px;
  background: #f5f5f5;
  font-family: Arial, sans-serif;
}

.wrap {
  width: 760px;
  margin: 0 auto;
  padding: 40px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 0 18px rgba(0, 0, 0, 0.12);
}

h1 {
  font-size: 28px;
  margin-bottom: 28px;
}

.insert-box {
  margin-bottom: 24px;
}

.insert-box strong,
.select-box strong {
  font-size: 18px;
  margin-right: 8px;
}

input[type="text"] {
  width: 220px;
  padding: 12px;
  font-size: 16px;
  border: 1px solid #bbb;
}

button {
  padding: 12px 20px;
  font-size: 15px;
  cursor: pointer;
  border: 1px solid #aaa;
  background: #eee;
}

select {
  padding: 10px;
  font-size: 16px;
}

.select-box {
  margin-bottom: 28px;
}

h2 {
  font-size: 22px;
  margin-bottom: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: white;
}

th, td {
  border: 1px solid #ddd;
  padding: 14px;
  text-align: center;
  font-size: 17px;
}

th {
  background: #eee;
  font-weight: bold;
}

td:nth-child(2) {
  text-align: center;
}

.empty {
  color: #777;
  font-weight: bold;
}
</style>
</head>

<body>

<div class="wrap">

  <h1>테이블선택해서 내용보기</h1>

  <!-- 입력값 삽입 -->
  <form method="post" class="insert-box">
    <strong>입력값 :</strong>

    <input type="hidden" name="table" value="<?= $table ?>">

    <input type="text" name="content" placeholder="문자열 입력">

    <button type="submit">삽입</button>
  </form>

  <!-- 테이블 선택 -->
  <div class="select-box">
    <strong>테이블선택</strong>

    <select onchange="changeTable(this.value)">
      <option value="tb_a" <?= $table == "tb_a" ? "selected" : "" ?>>테이블1</option>
      <option value="tb_b" <?= $table == "tb_b" ? "selected" : "" ?>>테이블2</option>
      <option value="tb_c" <?= $table == "tb_c" ? "selected" : "" ?>>테이블3</option>
    </select>
  </div>

  <h2>선택한 테이블의 리스트입니다.</h2>

  <table>
    <tr>
      <th>순번</th>
      <th>내용</th>
    </tr>

    <?php if (count($rows) > 0) { ?>
      <?php foreach ($rows as $row) { ?>
        <tr>
          <td><?= htmlspecialchars($row['idx']) ?></td>
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
  location.href = "ex10_db_study.php?table=" + tableName;
}
</script>

</body>
</html>

<?php
$pdo = null;
?>