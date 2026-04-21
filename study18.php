<?php

$arrayName = array('윤겸','규나', '민하', '은애');

// 배열 하나씩 직접 출력
echo $arrayName[0] . "<br>";
echo $arrayName[1] . "<br>";
echo $arrayName[2] . "<br>";
echo $arrayName[2] . "<br>";



// 반복문으로 출력
for($i = 0; $i < count($arrayName); $i++){
    echo $arrayName[$i] . "<br>";
}

?>