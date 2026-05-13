<?php

$name = $_GET['name'];
$gender = $_GET['gender'];
$height = $_GET['height'];
$weight = $_GET['weight'];

echo $name . "님의 결과입니다.<br><br>";

/* 남자일 경우 */
if($gender == "남자"){

    if($height >= 180 && $height <= 190 && $weight >= 70 && $weight <= 80){

        echo "핸섬맨입니다!";

    }
    else if(($height > 190 || $height < 160) && ($weight <= 50 || $weight > 100)){

        echo "추남입니다!";

    }
    else{

        echo "보통입니다!";

    }

}

/* 여자일 경우 */
else if($gender == "여자"){

    if($height >= 165 && $height <= 175 && $weight >= 50 && $weight <= 60){

        echo "미녀입니다!";

    }
    else if(($height > 180 || $height < 140) && ($weight <= 30 || $weight > 90)){

        echo "추녀입니다!";

    }
    else{

        echo "보통입니다!";

    }

}

?>