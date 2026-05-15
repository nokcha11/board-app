<?php
require_once "dbcon.php";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

/* 한글 깨짐 방지 */
mysqli_set_charset($conn, "utf8");

/* 선택한 테이블값 받기 */
$table = isset($_GET['table']) ? $_GET['table'] : "tb_a";

/* 허용할 테이블만 사용 */
$allowedTables = ["tb_a", "tb_b", "tb_c"];

if (!in_array($table, $allowedTables)) {
    $table = "tb_a";
}

/* =========================
   INSERT 처리
========================= */
if (isset($_POST['content'])) {

    $content = trim($_POST['content']);

    if ($content != "") {

        $sql = "INSERT INTO {$table} (content) VALUES (?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $content);

        $stmt->execute();

        $stmt->close();

        /* 새로고침시 중복 insert 방지 */
        header("Location: ex13_db_study.php?table=" . $table);        exit;
    }
}

/* =========================
   SELECT 처리
========================= */
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

  .insert-area{
    text-align:center;
    margin-bottom:25px;
  }

  .insert-area input{
    padding:10px;
    width:250px;
    font-size:16px;
  }

  .insert-area button{
    padding:10px 16px;
    font-size:16px;
    cursor:pointer;
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

  <!-- INSERT -->
  <form method="post" class="insert-area">

    <input type="hidden" name="table" value="<?= $table ?>">

    <input
      type="text"
      name="content"
      placeholder="문자열 입력"
    >

    <button type="submit">삽입</button>

  </form>

  <!-- TABLE SELECT -->
  <form method="get" id="tableForm">

    <div class="select-area">

      테이블선택

      <select name="table" onchange="changeTable()">

        <option value="tb_a"
          <?= $table == "tb_a" ? "selected" : "" ?>>
          테이블1
        </option>

        <option value="tb_b"
          <?= $table == "tb_b" ? "selected" : "" ?>>
          테이블2
        </option>

        <option value="tb_c"
          <?= $table == "tb_c" ? "selected" : "" ?>>
          테이블3
        </option>

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

          <td>
            <?= htmlspecialchars($row['content']) ?>
          </td>

        </tr>

      <?php } ?>

    <?php } else { ?>

      <tr>
        <td colspan="2" class="empty">
          데이터가 없습니다.
        </td>
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