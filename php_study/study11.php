<?php

$name = "홍길동";
$age = 20;

if($name=="홍길동" && $age>18)
    {
        echo "성인 홍길동입니다!";
    }
$loc1= "서울";
$loc2= "경기";

if($loc1=="충남" || $loc1=="충북" || $loc2=="서울" ||  $loc2=="경기" )
    {
        echo "<br>";
        echo "서울,경기 or 충남,충북 사람입니다.";
    }