<?php
require "functions.php";
if (isset($_POST["delete-pilot"])) {
  $pilotLinkId = checkInput($_POST["delete-pilot"]);
  $dp = new Pilots();
  $deletePilot = $dp->deletePilot($pilotLinkId);
  if ($deletePilot == 1) {
    header("Location: delete_pilot_success.php?success=1");
  } else if ($deletePilot == 0) {
    header("Location: delete_pilot_success.php?success=0");
  }
}
