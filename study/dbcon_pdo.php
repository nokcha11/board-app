<?php
$host = "localhost";
$dbname = "testdb";
$user = "root";
$password = "qwer";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "DB 연결 성공";

} catch (PDOException $e) {

    die("DB 연결 실패 : " . $e->getMessage());
}
?>