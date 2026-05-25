<?php
date_default_timezone_set('Asia/Seoul');
session_start();
require_once "dbcon.php";

if (!isset($_SESSION['idx'])) {
  echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
  exit;
}

$memberIdx = (int)$_SESSION['idx'];
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$todoDate = $_POST['todoDate'] ?? '';
$endDate = $_POST['endDate'] ?? '';
$titles = $_POST['titles'] ?? [];
$times = $_POST['times'] ?? [];
$todoGoal = trim($_POST['todoGoal'] ?? '');

if ($todoDate === '' || !is_array($titles) || empty($titles)) {
  echo "<script>alert('날짜와 할 일을 입력해주세요.'); history.back();</script>";
  exit;
}

if ($endDate === '') {
  $endDate = $todoDate;
}

if (strtotime($endDate) < strtotime($todoDate)) {
  echo "<script>alert('종료 날짜는 시작 날짜보다 빠를 수 없습니다.'); history.back();</script>";
  exit;
}

$endDateResult = $conn->query("SHOW COLUMNS FROM tb_todolist LIKE 'end_date'");
$hasEndDateColumn = ($endDateResult && $endDateResult->num_rows > 0);

if ($hasEndDateColumn) {
  $sql = "INSERT INTO tb_todolist (member_idx, due_date, end_date, todo_time, title, goal, status)
          VALUES (?, ?, ?, ?, ?, ?, ?)";
} else {
  $sql = "INSERT INTO tb_todolist (member_idx, due_date, todo_time, title, goal, status)
          VALUES (?, ?, ?, ?, ?, ?)";
}

$stmt = $conn->prepare($sql);

if (!$stmt) {
  echo "<script>alert('일정 저장 준비 중 오류가 발생했습니다. member_idx 컬럼을 확인해주세요.'); history.back();</script>";
  exit;
}

$insertCount = 0;
foreach ($titles as $i => $title) {
  $title = trim($title);

  if ($title === '') {
    continue;
  }

  $todoTime = $times[$i] ?? null;
  if ($todoTime === '') {
    $todoTime = null;
  }

  $status = 0;

  if ($hasEndDateColumn) {
    $stmt->bind_param("isssssi", $memberIdx, $todoDate, $endDate, $todoTime, $title, $todoGoal, $status);
    if ($stmt->execute()) {
      $insertCount++;
    }
  } else {
    $start = new DateTime($todoDate);
    $end = new DateTime($endDate);
    $end->modify('+1 day');
    $period = new DatePeriod($start, new DateInterval('P1D'), $end);

    foreach ($period as $day) {
      $dayText = $day->format('Y-m-d');
      $stmt->bind_param("issssi", $memberIdx, $dayText, $todoTime, $title, $todoGoal, $status);
      if ($stmt->execute()) {
        $insertCount++;
      }
    }
  }
}

$stmt->close();
$conn->close();

if ($insertCount > 0) {
  echo "<script>alert('일정이 추가되었습니다.'); location.href='ToDo_list.php';</script>";
} else {
  echo "<script>alert('추가할 일정이 없습니다.'); history.back();</script>";
}
?>
