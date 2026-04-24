<?php
$host = "localhost";
$dbname = "lie8220";
$user = "lie8220";
$password = "koko8220#";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "DB 연결 실패: " . $e->getMessage();
    exit;
}

// POST 값 받기
$id   = $_POST['id'] ?? '';
$pw   = $_POST['pw'] ?? '';
$pw2  = $_POST['pw2'] ?? '';
$name = $_POST['name'] ?? '';
$email= $_POST['email'] ?? '';

// 🔴 비밀번호 확인
if ($pw !== $pw2) {
    echo "<script>
            alert('비밀번호가 일치하지 않습니다.');
            history.back();
          </script>";
    exit;
}


// DB 저장
$sql = "INSERT INTO tb_member (id, pw, name, email, join_date) 
        VALUES (:id, :pw, :name, :email, NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $id,
    ':pw' => $pw,
    ':name' => $name,
    ':email' => $email
]);

// 완료 후 이동
echo "<script>
        alert('회원가입 완료!');
        location.href='login.html';
      </script>";
?>