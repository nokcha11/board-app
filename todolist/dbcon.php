<?php

$host = "localhost";
$user = "root";
$password = "1234";
$dbname = "testdb";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$con = $conn;
?>