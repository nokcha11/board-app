<?php
session_start();
require_once "dbcon.php";

$is_edit = false;
$row = [
    'idx' => '',
    'title' => '',
    'content' => '',
    'writer' => ''
];

if (isset($_GET['idx'])) {
    $is_edit = true;
    $idx = (int)$_GET['idx'];

    // 🔥 (조회수 증가)
    $sql_hit = "UPDATE tb_board SET hit = hit + 1 WHERE idx = :idx";
    $stmt_hit = $pdo->prepare($sql_hit);
    $stmt_hit->bindValue(':idx', $idx, PDO::PARAM_INT);
    $stmt_hit->execute();

    // 기존 코드 유지
    $sql = "SELECT * FROM tb_board WHERE idx = :idx";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':idx', $idx, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo "<script>alert('게시글을 찾을 수 없습니다.'); location.href='board.php';</script>";
        exit;
    }
  }

$can_edit = false;

if (isset($_SESSION['loginid'])) {
    if (!$is_edit) {
        $can_edit = true;
    } else if ($_SESSION['loginid'] == $row['writer'] || $_SESSION['loginid'] == 'admin') {
        $can_edit = true;
    }
}

if (!$can_edit && !$is_edit) {
    echo "<script>alert('로그인 후 글쓰기가 가능합니다.'); location.href='login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title><?php echo $is_edit ? '게시글' : '게시글 작성'; ?></title>
<link rel="stylesheet" href="css/style.css">

<style>
main {
    display: flex;
    justify-content: center;
    padding: 40px 0;
    background: url("images/pattern.png");
    background-size: cover;
    min-height: calc(100vh - 140px);
}

.write-box {
    width: 90%;
    max-width: 800px;
    background: rgba(15, 27, 51, 0.9);
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #1f3b66;
}

.write-box h2 {
    text-align: center;
    color: #4aa3ff;
}

.write-box input,
.write-box textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    background: #0b1220;
    color: #fff;
    border: 1px solid #1f3b66;
    border-radius: 6px;
    box-sizing: border-box;
}

.write-box textarea {
    height: 300px;
    resize: none;
}

.btn-area {
    text-align: center;
}

.btn-area button,
.btn-area a {
    display: inline-block;
    padding: 10px 18px;
    margin: 0 5px;
    border-radius: 5px;
    border: none;
    text-decoration: none;
    color: #fff;
    background: #4aa3ff;
    cursor: pointer;
}

.btn-area .delete {
    background: #d9534f;
}

.btn-area .back {
    background: #1f3b66;
}
</style>
</head>

<body>

<?php include "header.php"; ?>

<main>
    <div class="write-box">
        <h2><?php echo $is_edit ? '게시글' : '게시글 작성'; ?></h2>

        <form method="post" action="<?php echo $is_edit ? 'board_update_ok.php' : 'board_ok.php'; ?>">
            <?php if ($is_edit) { ?>
                <input type="hidden" name="idx" value="<?php echo $row['idx']; ?>">
            <?php } ?>

            <input type="text" name="title"
                   value="<?php echo htmlspecialchars($row['title']); ?>"
                   placeholder="제목"
                   <?php echo $can_edit ? '' : 'readonly'; ?>
                   required>

            <textarea name="content"
                      placeholder="내용"
                      <?php echo $can_edit ? '' : 'readonly'; ?>
                      required><?php echo htmlspecialchars($row['content']); ?></textarea>

            <div class="btn-area">
                <?php if ($can_edit) { ?>
                    <button type="submit"><?php echo $is_edit ? '수정완료' : '등록'; ?></button>
                <?php } ?>

                <?php if ($is_edit && $can_edit) { ?>
                    <a class="delete"
                       href="board_delete.php?idx=<?php echo $row['idx']; ?>"
                       onclick="return confirm('정말 삭제하시겠습니까?');">
                       삭제
                    </a>
                <?php } ?>

                <a class="back" href="board.php">목록</a>
            </div>
        </form>
    </div>
</main>

<footer>
    © 2026 MY WEBSITE | All Rights Reserved | Contact: admin@site.com
</footer>

</body>
</html>