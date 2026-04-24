<?php
  $host = "localhost";
  $dbname = "lie8220";
  $user = "lie8220";
  $password = "koko8220#";

  $conn = new mysqli($host, $user, $password, $dbname);

  if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
  }

  $idx = $_POST['idx'];
  $title = $_POST['snackName'];
  $count = $_POST['snackQty'];
  $price = $_POST['snackPrice'];
  $company = $_POST['snackCompany'];

  if (!$idx || !$title || !$count || !$price || !$company) {
    echo "<script>
            alert('모든 값을 입력해주세요.');
            history.back();
          </script>";
    exit;
  }

  $sql = "UPDATE tb_snack
          SET title = ?, count = ?, price = ?, company = ?
          WHERE idx = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("siisi", $title, $count, $price, $company, $idx);

  if ($stmt->execute()) {
    echo "<script>
            alert('수정되었습니다.');
            location.href='snack_list.php';
          </script>";
  } else {
    echo "수정 실패: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
?>