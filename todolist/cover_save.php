<?php
session_start();
require_once "dbcon.php";

if (!isset($_SESSION['idx'])) {
  echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
  exit;
}

$userIdx = (int)$_SESSION['idx'];
$titleText = $_POST['title_text'] ?? '';
$subText = $_POST['sub_text'] ?? '';

$sql = "
  INSERT INTO user_cover_texts (user_idx, title_text, sub_text)
  VALUES (?, ?, ?)
  ON DUPLICATE KEY UPDATE
    title_text = VALUES(title_text),
    sub_text = VALUES(sub_text)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $userIdx, $titleText, $subText);
$stmt->execute();

$stmt->close();

echo "<script>alert('커버 문구가 저장되었습니다.'); location.href='index.php';</script>";
?>