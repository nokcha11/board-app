<?php
  $host = "localhost";
  $dbname = "lie8220";
  $user = "lie8220";
  $password = "koko8220#";

  $conn = new mysqli($host, $user, $password, $dbname);

  if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
  }

  // POST 데이터 받기
  $due_date = $_POST['todoDate'];
  $title = $_POST['todoWork'];
  $goal = $_POST['todoGoal'];

  // 상태값: 체크박스가 없으면 기본 미완료 0
  $status = isset($_POST['status']) ? 1 : 0;

  if (!$title || !$goal || !$due_date) {
    echo "<script>
            alert('모든 값을 입력해주세요.');
            history.back();
          </script>";
    exit;
  }

  $sql = "INSERT INTO tb_todolist (title, goal, due_date, status)
          VALUES (?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssi", $title, $goal, $due_date, $status);

  if ($stmt->execute()) {
    echo "<script>
            alert('ToDo가 등록되었습니다.');
            location.href='ToDo_insert_ok.php';
          </script>";
  } else {
    echo "등록 실패: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
?>