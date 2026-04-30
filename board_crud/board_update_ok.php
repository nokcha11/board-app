<?php
session_start();
require_once "dbcon.php";

if (!isset($_SESSION['loginid'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='login.php';</script>";
    exit;
}

$idx = (int)($_POST['idx'] ?? 0);
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';

$sql = "SELECT writer FROM tb_board WHERE idx = :idx";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':idx', $idx, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "<script>alert('게시글이 없습니다.'); location.href='board.php';</script>";
    exit;
}

if ($_SESSION['loginid'] != $row['writer'] && $_SESSION['loginid'] != 'admin') {
    echo "<script>alert('수정 권한이 없습니다.'); location.href='board.php';</script>";
    exit;
}

$sql = "UPDATE tb_board
        SET title = :title, content = :content
        WHERE idx = :idx";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':title', $title);
$stmt->bindValue(':content', $content);
$stmt->bindValue(':idx', $idx, PDO::PARAM_INT);
$stmt->execute();

echo "<script>alert('수정되었습니다.'); location.href='board.php';</script>";
?>