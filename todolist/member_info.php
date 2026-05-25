<?php
session_start();
require_once "dbcon.php";

if (!isset($_SESSION['idx'], $_SESSION['loginid'])) {
  echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
  exit;
}

$memberIdx = (int)$_SESSION['idx'];
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$sql = "SELECT idx, id, name, email, join_date FROM tb_member WHERE idx = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $memberIdx);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();

if (!$member) {
  echo "<script>alert('회원 정보를 찾을 수 없습니다.'); location.href='index.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>나의 정보</title>
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/main.css">
</head>

<body>
<?php include "header.php"; ?>

<main>
  <div class="main-wrap">
    <section class="setup-box member-info-box">
      <h2>나의 정보</h2>
      <p>현재 로그인한 회원 정보입니다.</p>

      <div class="member-info-list">
        <div>
          <span>회원번호</span>
          <strong><?= (int)$member['idx'] ?></strong>
        </div>
        <div>
          <span>아이디</span>
          <strong><?= htmlspecialchars($member['id'], ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
        <div>
          <span>이름</span>
          <strong><?= htmlspecialchars($member['name'] ?? '-', ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
        <div>
          <span>이메일</span>
          <strong><?= htmlspecialchars($member['email'] ?? '-', ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
        <div>
          <span>가입일</span>
          <strong><?= htmlspecialchars($member['join_date'] ?? '-', ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
      </div>
    </section>
  </div>
</main>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
