<?php
// dbcon.php

// 1. DB 연결
$host = "localhost";
$user = "lie8220";      
$password = "koko8220#";  
$dbname = "lie8220";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $user, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "
    <script>
        alert('DB 연결 오류가 발생했습니다.');
        history.back();
    </script>
    ";
    exit;
}
?>