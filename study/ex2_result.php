<?php
$num1 = $_GET['num1'];
$num2 = $_GET['num2'];
$op = $_GET['op'];

if ($op == "+") {
    $result = $num1 + $num2;
} else if ($op == "-") {
    $result = $num1 - $num2;
} else if ($op == "*") {
    $result = $num1 * $num2;
} else if ($op == "/") {
    $result = $num1 / $num2;
}

echo "결과 : " . $result;
?>