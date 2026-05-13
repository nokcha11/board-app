<?php

$num1 = $_GET['num1'];
$num2 = $_GET['num2'];
$op = $_GET['op'];

function calc($num1, $num2){

    global $op;

    if($op == "+"){

        return $num1 + $num2;

    }
    else if($op == "-"){

        return $num1 - $num2;

    }
    else if($op == "*"){

        return $num1 * $num2;

    }
    else if($op == "/"){

        return $num1 / $num2;

    }

}

/* 함수 결과 저장 */
$result = calc($num1, $num2);

echo "결과 : " . $result;

?>