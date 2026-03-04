<?php
 include "../functions.php";

 if(isset($_GET["pilot-link-id"])){
 $pilotLinkId = $_GET["pilot-link-id"];
 }

 if(isset($_GET["paid"])){
 $paid = $_GET["paid"];
 }

 $ef = new Pilots();
 $upDateEntryFee = $ef->upDateEntryFee($pilotLinkId, $paid);

 if($upDateEntryFee == 1){
  echo "Kyllä";
 } else if ($upDateEntryFee == 0){
  echo "Ei";
 }
?>
