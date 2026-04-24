<?php
  $host = "localhost";
  $dbname = "lie8220";
  $user = "lie8220";
  $password = "koko8220#";

  $conn = new mysqli($host, $user, $password, $dbname);

  //연결 체크
if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

// POST 데이터 받기
$title = $_POST['snackName'];
$count = $_POST['snackQty'];
$price = $_POST['snackPrice'];
$company = $_POST['snackCompany'];

// 간단한 유효성 검사
if (!$title || !$count || !$price || !$company) {
    echo "<script>alert('모든 값을 입력해주세요.'); history.back();</script>";
    exit;
}

// SQL (보안: prepared statement 사용)
$sql = "INSERT INTO tb_snack (title, count, price, company) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("siis", $title, $count, $price, $company);

// 실행
if ($stmt->execute()) {
    echo "<script>
            alert('등록 완료!');
            location.href='snack_list.php';
          </script>";
} else {
    echo "오류 발생: " . $stmt->error;
}

// 종료
$stmt->close();
$conn->close();
?>