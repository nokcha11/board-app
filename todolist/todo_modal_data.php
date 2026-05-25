<?php
session_start();
require_once "dbcon.php";

if (!isset($_SESSION['idx'])) {
  http_response_code(401);
  echo "<p class='empty'>로그인이 필요합니다.</p>";
  exit;
}

$memberIdx = (int)$_SESSION['idx'];
$date = $_GET['date'] ?? date("Y-m-d");

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  http_response_code(500);
  echo "<p class='empty'>DB 연결 실패</p>";
  exit;
}

$conn->set_charset("utf8mb4");

$sql = "SELECT idx, due_date, todo_time, title, goal, status
        FROM tb_todolist
        WHERE member_idx = ? AND due_date = ?
        ORDER BY status ASC, todo_time ASC, idx DESC";
$stmt = $conn->prepare($sql);

if (!$stmt) {
  http_response_code(500);
  echo "<p class='empty'>일정을 불러올 수 없습니다.</p>";
  exit;
}

$stmt->bind_param("is", $memberIdx, $date);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="modal-todo-area">
  <?php if ($result->num_rows > 0) { ?>
    <?php while ($row = $result->fetch_assoc()) {
      $checked = ((int)$row['status'] === 1) ? "checked" : "";
      $doneClass = ((int)$row['status'] === 1) ? "done" : "";
      $timeText = !empty($row['todo_time']) ? date("H:i", strtotime($row['todo_time'])) : "";
    ?>
      <div class="modal-todo <?= $doneClass ?>">
        <div class="modal-todo-title">
          <input type="checkbox" <?= $checked ?> disabled>
          <span>
            <?php if ($timeText !== '') { ?>
              <b><?= htmlspecialchars($timeText, ENT_QUOTES, 'UTF-8') ?></b>
            <?php } ?>
            <?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?>
          </span>
        </div>

        <div class="modal-todo-btns">
          <a href="ToDo_edit.php?idx=<?= (int)$row['idx'] ?>">수정</a>
          <a href="ToDo_delete.php?idx=<?= (int)$row['idx'] ?>" onclick="return confirm('삭제하시겠습니까?')">삭제</a>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <p class="empty">이 날짜에는 일정이 없습니다.</p>
  <?php } ?>

  <div class="modal-add-btn">
    <a href="ToDo_insert.php?date=<?= urlencode($date) ?>">+ 이 날짜에 일정 추가</a>
  </div>
</div>

<?php
$stmt->close();
$conn->close();
?>
