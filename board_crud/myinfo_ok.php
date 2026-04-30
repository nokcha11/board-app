<?php
session_start();
require_once "dbcon.php";

if (!isset($_SESSION['loginid'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='login.php';</script>";
    exit;
}

$id = $_SESSION['loginid'];

$pw = trim($_POST['pw'] ?? '');
$pw2 = trim($_POST['pw2'] ?? '');
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($pw != '' || $pw2 != '') {
    if ($pw != $pw2) {
        echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
        exit;
    }

    $sql = "UPDATE tb_member
            SET pw = :pw,
                name = :name,
                email = :email
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':pw', $pw);
} else {
    $sql = "UPDATE tb_member
            SET name = :name,
                email = :email
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
}

$stmt->bindValue(':name', $name);
$stmt->bindValue(':email', $email);
$stmt->bindValue(':id', $id);
$stmt->execute();

echo "<script>alert('내 정보가 수정되었습니다.'); location.href='myinfo.php';</script>";
?>