<?php

$num1 = $_GET['num1'];
$num2 = $_GET['num2'];
$op = $_GET['op'];

function calc($num1, $num2){

    global $op;

    if($op == "+"){

        echo "결과 : " . ($num1 + $num2);

    }
    else if($op == "-"){

        echo "결과 : " . ($num1 - $num2);

    }
    else if($op == "*"){

        echo "결과 : " . ($num1 * $num2);

    }
    else if($op == "/"){

        echo "결과 : " . ($num1 / $num2);

    }

}

/* 함수 호출 */
calc($num1, $num2);

?>