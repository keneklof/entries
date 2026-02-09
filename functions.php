<?php
 //CHECKING THE INPUTS
function checkInput($data): string
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
} 
?>
