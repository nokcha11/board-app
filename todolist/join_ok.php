<?php
require_once "dbcon.php";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  echo "<script>alert('DB 연결 오류가 발생했습니다.'); history.back();</script>";
  exit;
}

$conn->set_charset("utf8mb4");

$id = trim($_POST['id'] ?? '');
$pw = trim($_POST['pw'] ?? '');
$pw2 = trim($_POST['pw2'] ?? '');
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($id === '' || $pw === '' || $pw2 === '' || $name === '' || $email === '') {
  echo "<script>alert('모든 항목을 입력해주세요.'); history.back();</script>";
  exit;
}

if ($pw !== $pw2) {
  echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
  exit;
}

$checkSql = "SELECT idx FROM tb_member WHERE id = ? LIMIT 1";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $id);
$checkStmt->execute();

if ($checkStmt->get_result()->num_rows > 0) {
  echo "<script>alert('이미 사용 중인 아이디입니다.'); history.back();</script>";
  exit;
}

$sql = "INSERT INTO tb_member (id, pw, name, email, join_date)
        VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);

if (!$stmt) {
  echo "<script>alert('회원가입 처리 중 오류가 발생했습니다.'); history.back();</script>";
  exit;
}

$stmt->bind_param("ssss", $id, $pw, $name, $email);

if ($stmt->execute()) {
  echo "<script>alert('회원가입이 완료되었습니다.'); location.href='login.php';</script>";
} else {
  echo "<script>alert('회원가입에 실패했습니다.'); history.back();</script>";
}

$checkStmt->close();
$stmt->close();
$conn->close();
?>
