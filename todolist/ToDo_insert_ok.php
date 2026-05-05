<?php
session_start();
require_once "dbcon.php";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$todoDate = $_POST['todoDate'] ?? '';
$titles = $_POST['titles'] ?? [];
$times = $_POST['times'] ?? []; // 시간 배열 받기
$todoGoal = $_POST['todoGoal'] ?? '';

if ($todoDate === '' || empty($titles)) {
  echo "<script>
          alert('날짜와 할 일을 입력해주세요.');
          history.back();
        </script>";
  exit;
}

// todo_time 컬럼까지 함께 저장
$sql = "INSERT INTO tb_todolist (due_date, todo_time, title, goal, status)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$insertCount = 0;

foreach ($titles as $i => $title) {
  $title = trim($title);

  if ($title === '') {
    continue;
  }

  // 해당 할 일의 시간값 가져오기
  $todoTime = $times[$i] ?? null;

  // 빈 시간은 NULL로 저장
  if ($todoTime === '') {
    $todoTime = null;
  }

  $status = 0;

  // 날짜, 시간, 제목, 목표, 상태 저장
  $stmt->bind_param("ssssi", $todoDate, $todoTime, $title, $todoGoal, $status);

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