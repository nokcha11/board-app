<?php

$count = $_GET['count'];
$msg = $_GET['msg'];

/* 전달값도 없고 리턴도 없는 함수 */
function test(){

    global $count;
    global $msg;

    for($i = 1; $i <= $count; $i++){

        echo $msg . "<br>";

    }

}

/* 함수 실행 */
test();

?>