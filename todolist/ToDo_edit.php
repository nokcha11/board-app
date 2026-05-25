<?php
session_start();
require_once "dbcon.php";

if (!isset($_SESSION['idx'])) {
  echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
  exit;
}

$memberIdx = (int)$_SESSION['idx'];
$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;

if ($idx <= 0) {
  echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
  exit;
}

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$columnResult = $conn->query("SHOW COLUMNS FROM tb_todolist LIKE 'member_idx'");
if (!$columnResult || $columnResult->num_rows === 0) {
  echo "<script>alert('tb_todolist.member_idx 컬럼이 필요합니다. 먼저 DB SQL을 실행해주세요.'); location.href='index.php';</script>";
  exit;
}

$sql = "SELECT idx, due_date, todo_time, title, goal, status
        FROM tb_todolist
        WHERE idx = ? AND member_idx = ?
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $idx, $memberIdx);
$stmt->execute();
$todo = $stmt->get_result()->fetch_assoc();

if (!$todo) {
  echo "<script>alert('수정할 수 없는 일정입니다.'); location.href='ToDo_list.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ToDo 수정</title>
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/list.css">
</head>

<body>
<?php include "header.php"; ?>

<div class="container">
  <h2>ToDo 수정</h2>

  <form action="ToDo_update.php" method="post">
    <input type="hidden" name="idx" value="<?= (int)$todo['idx'] ?>">

    <div class="input-group">
      <label for="todoDate">날짜</label>
      <input type="date" id="todoDate" name="todoDate" value="<?= htmlspecialchars($todo['due_date'], ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div class="input-group">
      <label for="todoTime">시간</label>
      <input type="time" id="todoTime" name="todoTime" value="<?= htmlspecialchars(substr((string)$todo['todo_time'], 0, 5), ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="input-group">
      <label for="title">할 일</label>
      <input type="text" id="title" name="title" value="<?= htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div class="input-group">
      <label for="todoGoal">목표</label>
      <textarea id="todoGoal" name="todoGoal"><?= htmlspecialchars($todo['goal'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="input-group">
      <label for="status">상태</label>
      <select id="status" name="status">
        <option value="0" <?= ((int)$todo['status'] === 0) ? 'selected' : '' ?>>진행중</option>
        <option value="1" <?= ((int)$todo['status'] === 1) ? 'selected' : '' ?>>완료</option>
      </select>
    </div>

    <div class="button-area">
      <button type="submit">수정</button>
      <button type="button" class="cancel-btn" onclick="history.back()">취소</button>
    </div>
  </form>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
