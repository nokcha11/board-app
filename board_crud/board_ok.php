<?php
session_start();
require_once "dbcon.php";

if (!isset($_SESSION['loginid'])) {
    echo "
    <script>
        alert('로그인 후 이용해주세요.');
        location.href='login.php';
    </script>
    ";
    exit;
}

$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$writer = $_SESSION['loginid'];

if ($title == '' || $content == '') {
    echo "
    <script>
        alert('제목과 내용을 입력해주세요.');
        history.back();
    </script>
    ";
    exit;
}

$sql = "INSERT INTO tb_board (title, content, writer)
        VALUES (:title, :content, :writer)";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':title', $title);
$stmt->bindValue(':content', $content);
$stmt->bindValue(':writer', $writer);
$stmt->execute();

echo "
<script>
    alert('게시글이 등록되었습니다.');
    location.href='board.php';
</script>
";
?>