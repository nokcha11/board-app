<?php
date_default_timezone_set('Asia/Seoul');
session_start();

if (!isset($_SESSION['idx'])) {
  echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
  exit;
}

$date = $_GET['date'] ?? "";
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ToDo 입력</title>
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/list.css">
  <!-- LIGHT -->
  <link id="header-light-theme" rel="stylesheet"href="css/header_bright_pastel_light.css">
  <link id="main-light-theme" rel="stylesheet" href="css/todo_light.css">

  <!--  DARK -->
  <link id="header-dark-theme" rel="stylesheet" href="css/header_glass_mood_light.css" disabled>
  <link id="main-dark-theme" rel="stylesheet" href="css/todo_dark.css" disabled>

  <script src="js/theme-mode.js" defer></script>
</head>

<body>
  <?php include "header.php"; ?>

  <div class="container">
    <h2>ToDoList</h2>

    <form action="ToDo_insert_ok.php" method="post" id="todoForm">
      <div class="input-group">
        <label for="todoDate">시작 날짜</label>
        <input type="date" id="todoDate" name="todoDate" value="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="input-group">
        <label for="endDate">종료 날짜</label>
        <input type="date" id="endDate" name="endDate" value="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>">
      </div>

      <div class="input-group">
        <label>할 일</label>
        <div id="todoList">
          <div class="todo-row">
            <input type="text" name="titles[]" class="todo-title" placeholder="할 일을 입력하세요" required>

            <details class="time-details">
              <summary>시간</summary>
              <div class="time-range">
                <input type="time" name="times[]" class="todo-time">
                <span>~</span>
                <input type="time" name="end_times[]" class="todo-time">
              </div>
            </details>

            <button type="button" class="plus-btn" onclick="addTodoInput()">+</button>
          </div>
        </div>
      </div>

      <div class="input-group">
        <label for="todoGoal">목표</label>
        <textarea id="todoGoal" name="todoGoal" placeholder="오늘의 목표를 입력하세요"></textarea>
      </div>

      <div class="button-area">
        <button type="submit">추가</button>
        <button type="button" class="cancel-btn" onclick="history.back()">취소</button>
      </div>
    </form>
  </div>

 <script>
function normalizeHourTime(value) {
  if (!value) return "";

  let hour = value.split(":")[0];

  if (hour.length === 1) {
    hour = "0" + hour;
  }

  return hour + ":00";
}

function applyTimeInputs() {
  document.querySelectorAll(".todo-time").forEach(function (input) {
    if (input.dataset.timeReady) return;

    input.dataset.timeReady = "true";
    input.step = 3600;

    input.addEventListener("focus", function () {
      if (!this.value) this.value = "00:00";
    });

    input.addEventListener("click", function () {
      if (!this.value) this.value = "00:00";
    });

    input.addEventListener("change", function () {
      this.value = normalizeHourTime(this.value);
    });

    input.addEventListener("blur", function () {
      this.value = normalizeHourTime(this.value);
    });
  });
}

function addTodoInput() {
  const todoList = document.getElementById("todoList");
  const row = document.createElement("div");
  row.className = "todo-row";

  row.innerHTML = `
    <input type="text" name="titles[]" class="todo-title" placeholder="할 일을 입력하세요" required>

    <details class="time-details">
      <summary>시간</summary>
      <div class="time-range">
        <input type="time" name="times[]" class="todo-time">
        <span>~</span>
        <input type="time" name="end_times[]" class="todo-time">
      </div>
    </details>

    <button type="button" class="minus-btn" onclick="this.parentElement.remove()">-</button>
  `;

  todoList.appendChild(row);
  applyTimeInputs();
}

applyTimeInputs();
</script>
</body>
</html>
