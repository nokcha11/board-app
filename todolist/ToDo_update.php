<?php
session_start();
require_once "dbcon.php";

if (!isset($_SESSION['idx'])) {
  echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
  exit;
}

$memberIdx = (int)$_SESSION['idx'];
$idx = isset($_POST['idx']) ? (int)$_POST['idx'] : 0;
$todoDate = $_POST['todoDate'] ?? '';
$todoTime = $_POST['todoTime'] ?? null;
$title = trim($_POST['title'] ?? '');
$todoGoal = trim($_POST['todoGoal'] ?? '');
$status = isset($_POST['status']) ? (int)$_POST['status'] : 0;

if ($idx <= 0 || $todoDate === '' || $title === '') {
  echo "<script>alert('필수 값을 확인해주세요.'); history.back();</script>";
  exit;
}

if ($todoTime === '') {
  $todoTime = null;
}

$status = $status === 1 ? 1 : 0;

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$checkSql = "SELECT idx FROM tb_todolist WHERE idx = ? AND member_idx = ? LIMIT 1";
$checkStmt = $conn->prepare($checkSql);

if (!$checkStmt) {
  echo "<script>alert('수정 처리 중 오류가 발생했습니다.'); history.back();</script>";
  exit;
}

$checkStmt->bind_param("ii", $idx, $memberIdx);
$checkStmt->execute();

if ($checkStmt->get_result()->num_rows === 0) {
  echo "<script>alert('수정할 수 없는 일정입니다.'); location.href='ToDo_list.php';</script>";
  exit;
}

$sql = "UPDATE tb_todolist
        SET due_date = ?, todo_time = ?, title = ?, goal = ?, status = ?
        WHERE idx = ? AND member_idx = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
  echo "<script>alert('수정 처리 중 오류가 발생했습니다.'); history.back();</script>";
  exit;
}

$stmt->bind_param("ssssiii", $todoDate, $todoTime, $title, $todoGoal, $status, $idx, $memberIdx);
$stmt->execute();

if ($stmt->affected_rows >= 0) {
  echo "<script>alert('수정되었습니다.'); location.href='ToDo_list.php';</script>";
} else {
  echo "<script>alert('수정할 수 없는 일정입니다.'); location.href='ToDo_list.php';</script>";
}

$checkStmt->close();
$stmt->close();
$conn->close();
?>
