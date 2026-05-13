<?php

$dan = $_GET['dan'];

for($i = 1; $i <= 9; $i++){

    echo $dan . " x " . $i . " = " . ($dan * $i);

    echo "<br>";
}



$dan = $_GET['dan'];

$i = 1;

while($i <= 9){

    echo $dan . " x " . $i . " = " . ($dan * $i);

    echo "<br>";

    $i++;
}



?>