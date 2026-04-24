<?php
  $host = "localhost";
  $dbname = "lie8220";
  $user = "lie8220";
  $password = "koko8220#";

  $conn = new mysqli($host, $user, $password, $dbname);

  if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
  }

  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  if ($page < 1) {
    $page = 1;
  }

  $list_num = 10;
  $start = ($page - 1) * $list_num;

  $count_sql = "SELECT COUNT(*) AS total FROM tb_snack";
  $count_result = $conn->query($count_sql);
  $count_row = $count_result->fetch_assoc();
  $total_count = $count_row['total'];

  $total_page = ceil($total_count / $list_num);

  $sql = "SELECT * FROM tb_snack ORDER BY idx DESC LIMIT $start, $list_num";
  $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>과자 목록</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container list-container">
    <h2>과자 목록</h2>

    <table class="snack-table">
      <thead>
        <tr>
          <th>순번</th>
          <th>과자명</th>
          <th>수량</th>
          <th>가격</th>
          <th>제조사</th>
          <th>관리</th>
        </tr>
      </thead>

      <tbody>
        <?php
          if ($result->num_rows > 0) {
            $num = $start + 1;

            while ($row = $result->fetch_assoc()) {
        ?>
              <tr>
                <td><?php echo $num; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['count']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['company']; ?></td>
                <td>
                  <a href="snack_edit.php?idx=<?php echo $row['idx']; ?>" class="edit-btn">수정</a>

                  <a href="snack_delete.php?idx=<?php echo $row['idx']; ?>"
                     class="delete-btn"
                     onclick="return confirm('삭제하시겠습니까?');">
                     삭제
                  </a>
                </td>
              </tr>
        <?php
              $num++;
            }
          } else {
        ?>
            <tr>
              <td colspan="6">등록된 과자가 없습니다.</td>
            </tr>
        <?php
          }
        ?>
      </tbody>
    </table>

    <div class="paging">
      <?php
        for ($i = 1; $i <= $total_page; $i++) {
          if ($i == $page) {
            echo "<strong>$i</strong>";
          } else {
            echo "<a href='snack_list.php?page=$i'>$i</a>";
          }
        }
      ?>
    </div>

    <div class="button-area">
      <button type="button" onclick="location.href='snack_insert.html'">등록하기</button>
    </div>
  </div>

</body>
</html>

<?php
  $conn->close();
?>