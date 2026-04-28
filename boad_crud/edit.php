<?php

require_once "dbcon.php";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "DB 연결 또는 쿼리 오류: " . $e->getMessage();
    exit;
}

$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;

if ($idx <= 0) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='admin.php';</script>";
    exit;
}

$sql = "SELECT * FROM tb_member WHERE idx = :idx";
$stmt = $pdo->prepare($sql);
$stmt->execute([':idx' => $idx]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "<script>alert('해당 회원 정보를 찾을 수 없습니다.'); location.href='admin.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>회원정보 수정</title>
<link rel="stylesheet" href="css/style.css">
<style>
main {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px 0;
    background: url("images/pattern.png");
    background-size: cover;
    min-height: calc(100vh - 140px);
}

.container {
    width: 90%;
    max-width: 1200px;
    display: flex;
    justify-content: center;
}

.edit-box {
    width: 100%;
    max-width: 360px;
    background: rgba(15, 27, 51, 0.9);
    padding: 45px 28px;
    border-radius: 12px;
    border: 1px solid #1f3b66;
}

.edit-box h2 {
    text-align: center;
    color: #4aa3ff;
    margin-bottom: 20px;
    font-size: 20px;
}

.edit-box input {
    width: 100%;
    padding: 11px;
    margin-bottom: 12px;
    border: 1px solid #1f3b66;
    border-radius: 6px;
    background: #0b1220;
    color: #fff;
    outline: none;
    box-sizing: border-box;
    font-size: 14px;
}

.edit-box input:focus {
    border-color: #4aa3ff;
}

.edit-box button {
    width: 100%;
    padding: 12px;
    margin-top: 8px;
    background: #4aa3ff;
    border: none;
    border-radius: 6px;
    color: #fff;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s;
}

.edit-box button:hover {
    background: #2f8fff;
}
</style>
</head>
<body>

<header>
    <h1>MY WEBSITE</h1>
    <nav>
        <a href="index.html">메인</a>
        <a href="login.html">로그인</a>
        <a href="join.html">회원가입</a>
        <a href="board.html">게시판</a>
        <a href="admin.php">회원관리</a>
    </nav>
</header>

<main>
    <div class="container">
        <div class="edit-box">
            <h2>회원정보 수정</h2>

            <form method="post" action="edit_ok.php">
                <input type="hidden" name="idx" value="<?php echo $row['idx']; ?>">

                <input type="text" name="id" value="<?php echo htmlspecialchars($row['id']); ?>" placeholder="아이디">
                <input type="password" name="pw" value="<?php echo htmlspecialchars($row['pw']); ?>" placeholder="패스워드">
                <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" placeholder="이름">
                <input type="text" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" placeholder="이메일">

                <button type="submit">수정하기</button>
            </form>
        </div>
    </div>
</main>

<footer>
    © 2026 MY WEBSITE | All Rights Reserved | Contact: admin@site.com
</footer>

</body>
</html>