<?php
require_once "dbcon.php";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

/* 선택한 테이블값 받기 */
$table = isset($_GET['table']) ? $_GET['table'] : "tb_a";

/* 허용할 테이블만 사용 */
$allowedTables = ["tb_a", "tb_b", "tb_c"];

if (!in_array($table, $allowedTables)) {
    $table = "tb_a";
}

/* 선택한 테이블 조회 */
$sql = "SELECT idx, content FROM $table ORDER BY idx DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>테이블 선택해서 내용보기</title>

<style>
  body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    padding: 50px;
  }

  .wrap {
    width: 700px;
    margin: 0 auto;
    background: white;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
  }

  h1 {
    text-align: center;
    margin-bottom: 35px;
  }

  .select-area {
    text-align: center;
    margin-bottom: 35px;
    font-size: 20px;
    font-weight: bold;
  }

  select {
    padding: 8px 16px;
    font-size: 18px;
    margin-left: 12px;
  }

  h2 {
    font-size: 22px;
    margin-bottom: 18px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th, td {
    border: 1px solid #ccc;
    padding: 12px;
    text-align: center;
  }

  th {
    background: #eeeeee;
  }

  td:nth-child(2) {
    text-align: left;
  }

  .empty {
    text-align: center;
    color: #777;
    font-weight: bold;
  }
</style>
</head>

<body>

<div class="wrap">

  <h1>테이블선택해서 내용보기</h1>

  <form method="get" id="tableForm">
    <div class="select-area">
      테이블선택

      <select name="table" onchange="changeTable()">
        <option value="tb_a" <?= $table == "tb_a" ? "selected" : "" ?>>테이블1</option>
        <option value="tb_b" <?= $table == "tb_b" ? "selected" : "" ?>>테이블2</option>
        <option value="tb_c" <?= $table == "tb_c" ? "selected" : "" ?>>테이블3</option>
      </select>
    </div>
  </form>

  <h2>선택한 테이블의 리스트입니다.</h2>

  <table>
    <tr>
      <th>순번</th>
      <th>내용</th>
    </tr>

    <?php if ($result && $result->num_rows > 0) { ?>
      <?php while ($row = $result->fetch_assoc()) { ?>
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
function changeTable() {
  document.getElementById("tableForm").submit();
}
</script>

</body>
</html>

<?php
$conn->close();
?>