<?php

session_start();

require_once "dbcon.php";

header(
  "Content-Type: application/json; charset=utf-8"
);

if (!isset($_SESSION["idx"])) {

  echo json_encode([

    "success" => false,

    "message" => "로그인이 필요합니다."

  ], JSON_UNESCAPED_UNICODE);

  exit;
}

$userIdx =
  (int)$_SESSION["idx"];

$todoIdx =
  isset($_POST["idx"])

    ? (int)$_POST["idx"]

    : 0;

$status =
  isset($_POST["status"])

    ? (int)$_POST["status"]

    : 0;

if ($todoIdx <= 0) {

  echo json_encode([

    "success" => false,

    "message" => "잘못된 일정입니다."

  ], JSON_UNESCAPED_UNICODE);

  exit;
}

$sql = "

  UPDATE tb_todolist

  SET status = ?

  WHERE idx = ?
  AND member_idx = ?

";

$stmt =
  $conn->prepare($sql);

$stmt->bind_param(

  "iii",

  $status,
  $todoIdx,
  $userIdx
);

$result =
  $stmt->execute();

echo json_encode([

  "success" => $result,

  "status" => $status

], JSON_UNESCAPED_UNICODE);