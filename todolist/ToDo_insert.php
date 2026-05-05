<?php
// 모달/달력에서 date 값을 넘기면 날짜 자동 입력
$date = isset($_GET['date']) ? $_GET['date'] : "";
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>ToDoList 입력</title>
  <link rel="stylesheet" href="css/list.css">
</head>

<body>

  <div class="container">
    <h2>ToDoList</h2>

    <form action="ToDo_insert_ok.php" method="post" id="todoForm">

      <div class="input-group">
        <label for="todoDate">날짜</label>
        <input type="date" id="todoDate" name="todoDate" value="<?= htmlspecialchars($date) ?>" required>
      </div>

      <div class="input-group">
        <label>할 일</label>

        <!-- 시간 + 할 일을 여러 개 추가하는 영역 -->
        <div id="todoList">
          <div class="todo-row">
            <!-- 일정 시간 입력 -->
            <input type="time" name="times[]" class="todo-time">

            <!-- 여러 할 일 배열 입력 -->
            <input type="text" name="titles[]" placeholder="할 일을 입력하세요" required>

            <!-- 할 일 입력칸 추가 버튼 -->
            <button type="button" class="plus-btn" onclick="addTodoInput()">+</button>
          </div>
        </div>
      </div>

      <div class="input-group">
        <label for="todoGoal">목표</label>
        <textarea id="todoGoal" name="todoGoal" placeholder="오늘의 목표를 입력하세요" required></textarea>
      </div>

      <div class="button-area">
        <button type="submit">추가</button>
        <button type="button" class="cancel-btn" onclick="history.back()">취소</button>
      </div>

    </form>
  </div>

  <script>
    function addTodoInput() {
      const todoList = document.getElementById("todoList");

      const row = document.createElement("div");
      row.className = "todo-row";

      // 새 할 일 행 추가
      row.innerHTML = `
        <input type="time" name="times[]" class="todo-time">
        <input type="text" name="titles[]" placeholder="할 일을 입력하세요" required>
        <button type="button" class="minus-btn" onclick="this.parentElement.remove()">-</button>
      `;

      todoList.appendChild(row);
    }
  </script>

</body>
</html>