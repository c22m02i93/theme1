<?php 
//note that date may have slashes or dots so we url decode it

$date = urldecode ($_GET['datepicker']);

echo "chosen date is: ".$date;
?>