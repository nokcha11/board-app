<?php
session_start();
require_once __DIR__ . "/dbcon.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["idx"])) {
  echo json_encode([
    "success" => false,
    "message" => "로그인이 필요합니다."
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

$userIdx = (int)$_SESSION["idx"];

$title = $_POST["title"] ?? "";
$authors = $_POST["authors"] ?? "";
$thumbnail = $_POST["thumbnail"] ?? "";

if ($title === "") {
  echo json_encode([
    "success" => false,
    "message" => "도서 제목이 없습니다."
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

/*
  dbcon.php에서 mysqli 객체 이름이
  $conn, $mysqli, $db 중 무엇이든 잡히게 처리
*/
$db = null;

if (isset($conn) && $conn instanceof mysqli) {
  $db = $conn;
} elseif (isset($mysqli) && $mysqli instanceof mysqli) {
  $db = $mysqli;
} elseif (isset($dbconn) && $dbconn instanceof mysqli) {
  $db = $dbconn;
}

if (!$db) {
  echo json_encode([
    "success" => false,
    "message" => "DB 연결 객체를 찾을 수 없습니다."
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

$sql = "
  INSERT INTO user_books
    (user_idx, title, authors, thumbnail)
  VALUES
    (?, ?, ?, ?)
";

$stmt = $db->prepare($sql);

if (!$stmt) {
  echo json_encode([
    "success" => false,
    "message" => "SQL prepare 실패: " . $db->error
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

$stmt->bind_param(
  "isss",
  $userIdx,
  $title,
  $authors,
  $thumbnail
);

$result = $stmt->execute();

echo json_encode([
  "success" => $result,
  "message" => $result ? "저장 성공" : "저장 실패: " . $stmt->error
], JSON_UNESCAPED_UNICODE);