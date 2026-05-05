<?php
session_start();
require_once "dbcon.php";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$todoDate = $_POST['todoDate'] ?? '';
$titles = $_POST['titles'] ?? [];
$todoGoal = $_POST['todoGoal'] ?? '';

if ($todoDate === '' || empty($titles)) {
  echo "<script>
          alert('날짜와 할 일을 입력해주세요.');
          history.back();
        </script>";
  exit;
}

$sql = "INSERT INTO tb_todolist (due_date, title, goal, status)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$insertCount = 0;

foreach ($titles as $title) {
  $title = trim($title);

  if ($title === '') {
    continue;
  }

  $status = 0;

  $stmt->bind_param("sssi", $todoDate, $title, $todoGoal, $status);

  if ($stmt->execute()) {
    $insertCount++;
  }
}

$stmt->close();
$conn->close();

if ($insertCount > 0) {
  echo "<script>
          alert('일정이 추가되었습니다.');
          location.href='ToDo_list.php';
        </script>";
} else {
  echo "<script>
          alert('추가된 일정이 없습니다.');
          history.back();
        </script>";
}
?>