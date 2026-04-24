<?php
  $host = "localhost";
  $dbname = "lie8220";
  $user = "lie8220";
  $password = "koko8220#";

  $conn = new mysqli($host, $user, $password, $dbname);

  if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
  }

  $idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;

  if ($idx <= 0) {
    echo "<script>
            alert('잘못된 접근입니다.');
            history.back();
          </script>";
    exit;
  }

  $sql = "SELECT * FROM tb_snack WHERE idx = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $idx);
  $stmt->execute();

  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  if (!$row) {
    echo "<script>
            alert('데이터가 없습니다.');
            history.back();
          </script>";
    exit;
  }
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>과자 정보 수정</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container">
    <h2>과자 정보 수정</h2>

    <form action="snack_edit_ok.php" method="post">
      <input type="hidden" name="idx" value="<?php echo $row['idx']; ?>">

      <div class="input-group">
        <label for="snackName">과자명</label>
        <input type="text" id="snackName" name="snackName" value="<?php echo $row['title']; ?>" required>
      </div>

      <div class="input-group">
        <label for="snackQty">과자수량</label>
        <input type="number" id="snackQty" name="snackQty" value="<?php echo $row['count']; ?>" required>
      </div>

      <div class="input-group">
        <label for="snackPrice">과자가격</label>
        <input type="number" id="snackPrice" name="snackPrice" value="<?php echo $row['price']; ?>" required>
      </div>

      <div class="input-group">
        <label for="snackCompany">과자제조사</label>
        <input type="text" id="snackCompany" name="snackCompany" value="<?php echo $row['company']; ?>" required>
      </div>

      <div class="button-area">
        <button type="submit">수정</button>
        <button type="button" class="cancel-btn" onclick="location.href='snack_list.php'">취소</button>
      </div>
    </form>
  </div>

</body>
</html>

<?php
  $stmt->close();
  $conn->close();
?>