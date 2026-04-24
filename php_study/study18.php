<?php
$arrNames = array('윤겸','규나','민하','은애','윤겸2','규나2','민하2','은애2');
echo $arrNames[0]."<br>";
echo $arrNames[1]."<br>";
echo $arrNames[2]."<br>";
echo $arrNames[3]."<br>";
echo $arrNames[4]."<br>";
echo $arrNames[5]."<br>";
echo $arrNames[6]."<br>";
echo $arrNames[7]."<br>";


for($i=1; $i<count($arrNames); $i++)
  {
    echo $arrNames[$i]."<br>";
  }

?>