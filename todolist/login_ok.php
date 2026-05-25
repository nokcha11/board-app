<?php
session_start();
require_once "dbcon.php";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  echo "<script>alert('DB 연결 오류가 발생했습니다.'); history.back();</script>";
  exit;
}

$conn->set_charset("utf8mb4");

$id = trim($_POST['id'] ?? '');
$pw = trim($_POST['pw'] ?? '');

if ($id === '' || $pw === '') {
  echo "<script>alert('아이디와 비밀번호를 입력해주세요.'); location.href='login.php';</script>";
  exit;
}

$sql = "SELECT idx, id FROM tb_member WHERE id = ? AND pw = ? LIMIT 1";
$stmt = $conn->prepare($sql);

if (!$stmt) {
  echo "<script>alert('로그인 처리 중 오류가 발생했습니다.'); history.back();</script>";
  exit;
}

$stmt->bind_param("ss", $id, $pw);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
  $_SESSION['idx'] = (int)$row['idx'];
  $_SESSION['loginid'] = $row['id'];

  echo "<script>alert('로그인 성공'); location.href='index.php';</script>";
} else {
  echo "<script>alert('로그인 실패'); location.href='login.php';</script>";
}

$stmt->close();
$conn->close();
?>
