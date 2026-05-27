<?php
session_start();
require_once __DIR__ . "/dbcon.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["idx"])) {
  echo json_encode(["success" => false, "message" => "로그인이 필요합니다."], JSON_UNESCAPED_UNICODE);
  exit;
}

$userIdx = (int)$_SESSION["idx"];

$sql = "
  SELECT title, authors, thumbnail
  FROM user_books
  WHERE user_idx = ?
  ORDER BY created_at DESC, id DESC
  LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userIdx);
$stmt->execute();

$result = $stmt->get_result();
$book = $result->fetch_assoc();

echo json_encode([
  "success" => true,
  "book" => $book
], JSON_UNESCAPED_UNICODE);