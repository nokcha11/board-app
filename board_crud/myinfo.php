<?php
session_start();
require_once "dbcon.php";

// 로그인 안 했으면 로그인 페이지로 이동
if (!isset($_SESSION['loginid'])) {
    echo "
    <script>
        alert('로그인 후 이용해주세요.');
        location.href='login.php';
    </script>
    ";
    exit;
}

$loginid = $_SESSION['loginid'];

// 내 정보 조회
$sql = "SELECT id, pw, name, email FROM tb_member WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $loginid);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "
    <script>
        alert('회원 정보를 찾을 수 없습니다.');
        location.href='index.php';
    </script>
    ";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>내 정보 수정</title>

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

.info-box {
    width: 100%;
    max-width: 360px;
    background: rgba(15, 27, 51, 0.9);
    padding: 45px 28px;
    border-radius: 12px;
    border: 1px solid #1f3b66;
}

.info-box h2 {
    text-align: center;
    color: #4aa3ff;
    margin-bottom: 20px;
    font-size: 20px;
}

.info-box label {
    display: block;
    color: #8fbaff;
    font-size: 12px;
    margin-bottom: 5px;
    margin-left: 2px;
}

.info-box input {
    width: 100%;
    padding: 11px;
    margin-bottom: 15px;
    border: 1px solid #1f3b66;
    border-radius: 6px;
    background: #0b1220;
    color: #fff;
    outline: none;
    box-sizing: border-box;
    font-size: 14px;
}

.info-box input:focus {
    border-color: #4aa3ff;
}

.info-box input[readonly] {
    background: #161f2e;
    color: #777;
    cursor: not-allowed;
}

.info-box button {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    background: #4aa3ff;
    border: none;
    border-radius: 6px;
    color: #fff;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s;
}

.info-box button:hover {
    background: #2f8fff;
}

.info-box .btn-cancel {
    display: block;
    text-align: center;
    text-decoration: none;
    color: #777;
    font-size: 13px;
    margin-top: 15px;
}

.info-box .btn-cancel:hover {
    color: #ccc;
}
</style>
</head>

<body>

<?php include "header.php"; ?>

<main>
    <div class="container">
        <div class="info-box">

            <h2>내 정보 수정</h2>

            <form method="post" action="myinfo_ok.php" onsubmit="return checkForm()">
                <label>아이디</label>
                <input type="text" value="<?php echo htmlspecialchars($row['id']); ?>" name="id" readonly>

                <label>새 패스워드 (변경 시 입력)</label>
                <input type="password" placeholder="새 패스워드" name="pw" id="pw">

                <label>패스워드 확인</label>
                <input type="password" placeholder="패스워드 확인" name="pw2" id="pw2">

                <label>이름</label>
                <input type="text" value="<?php echo htmlspecialchars($row['name']); ?>" name="name">

                <label>이메일</label>
                <input type="text" value="<?php echo htmlspecialchars($row['email']); ?>" name="email">

                <button type="submit">수정완료</button>
                <a href="index.php" class="btn-cancel">취소하고 돌아가기</a>
            </form>

        </div>
    </div>
</main>

<footer>
    © 2026 MY WEBSITE | All Rights Reserved | Contact: admin@site.com
</footer>

<script>
function checkForm() {
    const pw = document.getElementById("pw").value;
    const pw2 = document.getElementById("pw2").value;

    if (pw !== "" || pw2 !== "") {
        if (pw !== pw2) {
            alert("비밀번호가 일치하지 않습니다.");
            return false;
        }
    }
    return true;
}
</script>

</body>
</html>