<?php
gugudan();

function gugudan()
{
  $dan=2;$su=1;
  while($dan<=9)
    {    
      while($su<=9)
      {
        echo $dan." x ".$su." = ".$dan*$su."<br>";
        $su++;
      }
      $su=1;
      $dan++;
    }
}
?>