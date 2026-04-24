<?php

$host = "localhost";
$user = "lie8220";
$password = "koko8220#";
$dbname = "lie8220";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "DB 연결 또는 쿼리 오류: " . $e->getMessage();
    exit;
}

$idx   = isset($_POST['idx']) ? (int)$_POST['idx'] : 0;
$id    = trim($_POST['id'] ?? '');
$pw    = trim($_POST['pw'] ?? '');
$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($idx <= 0) {
    echo "<script>
            alert('잘못된 접근입니다.');
            location.href='admin.php';
          </script>";
    exit;
}

$sql = "UPDATE tb_member 
        SET id = :id,
            pw = :pw,
            name = :name,
            email = :email
        WHERE idx = :idx";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $id,
    ':pw' => $pw,
    ':name' => $name,
    ':email' => $email,
    ':idx' => $idx
]);

echo "<script>
        alert('수정 완료');
        location.href='admin.php';
      </script>";
?>