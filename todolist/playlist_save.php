<?php
session_start();
require_once "dbcon.php";

if (!isset($_SESSION['idx'])) {
  echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
  exit;
}

$memberIdx = (int)$_SESSION['idx'];
$title = $_POST['playlist_title'] ?? '';
$url = $_POST['playlist_url'] ?? '';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$sql = "UPDATE tb_member SET playlist_title = ?, playlist_url = ? WHERE idx = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $title, $url, $memberIdx);
$stmt->execute();

$stmt->close();
$conn->close();

echo "<script>alert('플레이리스트가 저장되었습니다.'); location.href='index.php';</script>";
?>