<?php
$host = "localhost";
$dbname = "lie8220";
$user = "lie8220";
$password = "koko8220#";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$idx = isset($_GET['idx']) ? intval($_GET['idx']) : 0;

if ($idx <= 0) {
  echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
  exit;
}

$sql = "DELETE FROM tb_todolist WHERE idx = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idx);

if ($stmt->execute()) {
  echo "<script>
          alert('삭제되었습니다.');
          location.href='ToDo_list.php';
        </script>";
} else {
  echo "<script>
          alert('삭제 실패');
          history.back();
        </script>";
}

$stmt->close();
$conn->close();
?>