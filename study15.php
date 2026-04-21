<?php

$dan = 2;

while($dan <= 9) 
{

    while($su <= 9) 
    {
        echo $dan . " X " . $su . " = " . $dan*$su . "<br>";
        $su++; 
    }

    $su = 1; 
    $dan++; 
}

?>