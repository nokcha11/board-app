<?php
session_start();

require_once "dbcon.php";

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

// POST 값 받기
$id = $_POST['id'] ?? '';
$pw = $_POST['pw'] ?? '';

if ($id == '' || $pw == '') {
    echo "
    <script>
        alert('아이디와 비밀번호를 입력해주세요.');
        location.href='login.php';
    </script>
    ";
    exit;
}

// DB조회 회원 확인
$sql = "SELECT * FROM tb_member WHERE id = :id AND pw = :pw";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->bindValue(':pw', $pw);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $_SESSION['loginid'] = $id;

    echo "
    <script>
        alert('로그인 성공');
        location.href='index.php';
    </script>
    ";
} else {
    echo "
    <script>
        alert('로그인 실패');
        location.href='login.php';
    </script>
    ";
}
?>