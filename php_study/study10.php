<?php
$num = "aaa";
if($num == '한')
    {
        echo "'한' 하고 같은 글자입니다.";
    }
else{
        echo "'한' 하고 다른 글자입니다.";
    }
$num = strlen($num);
if($num == 3) { echo "글자갯수가 3개입니다.";}
else          { echo "글자갯수가 3개가 아닙니다.";}

if(strlen($num) == 3) { echo "글자갯수가 3개입니다.";}
else          { echo "글자갯수가 3개가 아닙니다.";}

?>