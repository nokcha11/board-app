<?php
require_once "dbcon.php";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("DB 연결 실패");
}

$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

$sql = "SELECT * FROM tb_todolist 
        WHERE due_date = ?
        ORDER BY status ASC, idx DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="modal-todo-area">

  <?php if ($result->num_rows > 0) { ?>
    <?php while ($row = $result->fetch_assoc()) {
      $checked = $row['status'] == 1 ? "checked" : "";
      $doneClass = $row['status'] == 1 ? "done" : "";
    ?>
      <div class="modal-todo <?= $doneClass ?>">
        <div class="modal-todo-title">
          <input type="checkbox" <?= $checked ?> disabled>
          <span><?= htmlspecialchars($row['title']) ?></span>
        </div>

        <div class="modal-todo-btns">
          <a href="ToDo_edit.php?idx=<?= $row['idx'] ?>">수정</a>
          <a href="ToDo_delete.php?idx=<?= $row['idx'] ?>" onclick="return confirm('삭제하시겠습니까?')">삭제</a>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <p class="empty">이 날짜에는 일정이 없습니다.</p>
  <?php } ?>

  <div class="modal-add-btn">
    <a href="ToDo_insert.html?date=<?= urlencode($date) ?>">+ 이 날짜에 일정 추가</a>
  </div>

</div>

<?php
$stmt->close();
$conn->close();
?>