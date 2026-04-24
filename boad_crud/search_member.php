<?php
$host = "localhost";
$dbname = "lie8220";
$user = "lie8220";      // ← 본인 DB 계정으로 수정
$password = "koko8220#"; // ← 본인 비밀번호로 수정

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $user, $password);

    // 에러 출력 설정
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 쿼리 실행
    $sql = "SELECT * FROM tb_member";
    $stmt = $pdo->query($sql);

    // 결과 출력
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "idx: " . $row['idx'] . " / ";
        echo "id: " . $row['id'] . " / ";
        echo "pw: " . $row['pw'] . " / ";
        echo "name: " . $row['name'] . " / ";
        echo "email: " . $row['email'] . "<br>";
    }

} catch (PDOException $e) {
    echo "DB 연결 실패: " . $e->getMessage();
}
?>