<?php

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
    echo "DB 오류: " . $e->getMessage();
    exit;
}

// 2. idx 값 받기
$idx = $_GET['idx'] ?? 0;

// 3. 잘못된 접근 방지
if (!$idx) {
    echo "<script>
            alert('잘못된 접근입니다.');
            location.href='admin.php';
          </script>";
    exit;
}

// 4. 삭제 쿼리 실행
$sql = "DELETE FROM tb_member WHERE idx = :idx";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':idx' => $idx
]);

// 5. 삭제 후 이동
echo "<script>
        alert('삭제 완료');
        location.href='admin.php';
      </script>";
?>