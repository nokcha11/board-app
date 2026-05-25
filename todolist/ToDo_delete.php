<?php
session_start();
require_once "dbcon.php";

if (!isset($_SESSION['idx'])) {
  echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
  exit;
}

$memberIdx = (int)$_SESSION['idx'];
$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;

if ($idx <= 0) {
  echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
  exit;
}

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$sql = "DELETE FROM tb_todolist WHERE idx = ? AND member_idx = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
  echo "<script>alert('삭제 처리 중 오류가 발생했습니다.'); history.back();</script>";
  exit;
}

$stmt->bind_param("ii", $idx, $memberIdx);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo "<script>alert('삭제되었습니다.'); location.href='ToDo_list.php';</script>";
} else {
  echo "<script>alert('삭제할 수 없는 일정입니다.'); location.href='ToDo_list.php';</script>";
}

$stmt->close();
$conn->close();
?>
