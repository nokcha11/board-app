<?php

// 1. DB 접속 정보
$host = "localhost";
$user = "lie8220";      // dothome 아이디
$password = "koko8220#";         // 보통 dothome은 비밀번호 있음 (본인 비번 넣기)
$dbname = "lie8220";

// 2. DB 연결
$conn = mysqli_connect($host, $user, $password, $dbname);

// 연결 확인
if(!$conn){
    die("DB 연결 실패: " . mysqli_connect_error());
}

// 3. 쿼리 실행
$sql = "SELECT * FROM tb_member";
$result = mysqli_query($conn, $sql);

// 4. 데이터 출력
if(mysqli_num_rows($result) > 0){

    while($row = mysqli_fetch_assoc($result)){
        echo "번호: " . $row['idx'] . "<br>";
        echo "아이디: " . $row['id'] . "<br>";
        echo "비밀번호: " . $row['pw'] . "<br>";
        echo "이름: " . $row['name'] . "<br>";
        echo "이메일: " . $row['email'] . "<br>";
        echo "-----------------------------<br>";
    }

}else{
    echo "데이터가 없습니다.";
}

// 5. 연결 종료
mysqli_close($conn);

?>