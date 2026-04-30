<?php
session_start();

// 세션 변수 전체 삭제
$_SESSION = [];

// 세션 쿠키 삭제 (중요)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 세션 자체 삭제
session_destroy();

// 이동
echo "
<script>
    alert('로그아웃 되었습니다.');
    location.href='index.php';
</script>
";
?>