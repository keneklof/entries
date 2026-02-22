<?php
include "variables.php";
include "language.php";
include "functions.php";

$pilotLinkId = "c7sm2026699b2870b6317";

//$competitionId = 1;
//$c = new Competitions();
//$competitionInfo = $c->selectCompetitionInfo($competitionId);

$ud = new Pilots();

$pilotInfo = $ud->selectSinglePilotInfo($competitionId, $pilotLinkId);

echo $pilotInfo[0]["pilot_first_name"]."<br>";
echo $pilotInfo[0]["pilot_last_name"]."<br>";
echo $pilotInfo[0]["plane_type"]."<br>";

//HANDLING UPDATE FORM INPUTS
//Error array for form inputs
if (isset($_POST["update"])) {
  $errorArray = [];

  //Pilot first name
  if (isset($_POST["pilot-first-name"])) {
    $firstName = checkInput($_POST["pilot-first-name"]);
  }

  //Pilot last name
  if (isset($_POST["pilot-last-name"])) {
    $lastName = checkInput($_POST["pilot-last-name"]);
  }

  //Pilot phone
  if (isset($_POST["pilot-phone"])) {
    $phone = checkInput($_POST["pilot-phone"]);
  }

  //Pilot email
  if (isset($_POST["pilot-email"])) {
    $email = checkInput($_POST["pilot-email"]);
  }

  //Pilot club
  if (isset($_POST["pilot-club"]) && $_POST["pilot-club"] != "") {
    $club = checkInput($_POST["pilot-club"]);
  } else {
    $club = "Ei kerhoa";
  }

  //Pilot country
  if ($competitionInfo[0]["competition_international"] == 1) {
    if (isset($_POST["pilot-country"]) && $_POST["pilot-country"] != 0) {
      $country = checkInput($_POST["pilot-country"]);
    } else {
      array_push($errorArray, $language[$l]["error-form-country"]);
    }
  } else if ($competitionInfo[0]["competition_international"] == 0) {
    $country = 1;
  }

  //Plane type
  if (isset($_POST["plane-type"])) {
    $glider = checkInput($_POST["plane-type"]);
  }

  //Plane register
  if (isset($_POST["plane-register"])) {
    $register = strtoupper(checkInput($_POST["plane-register"]));
  }

  //Plane competition sign
  if (isset($_POST["plane-competition-sign"])) {
    $competitionSign = strtoupper(checkInput($_POST["plane-competition-sign"]));
    //Creating an indidual string for the pilot
    $pilotLinkId = strtolower($competitionSign) . uniqid("sm2026");
  }

  //Plane wingspan
  if (isset($_POST["plane-wingspan"])) {
    $wingspan = checkInput($_POST["plane-wingspan"]);
  }

  //Plane winglet
  if (isset($_POST["plane-winglets"]) && $_POST["plane-winglets"] != 0) {
    $winglets = checkInput($_POST["plane-winglets"]);
  } else {
    array_push($errorArray, $language[$l]["error-form-winglets"]);
  }

  //Plane engine
  if (isset($_POST["plane-engine"]) && $_POST["plane-engine"] != 0) {
    $engine = checkInput($_POST["plane-engine"]);
  } else {
    array_push($errorArray, $language[$l]["error-form-engine"]);
  }

  //Plane Flarm ID
  if (isset($_POST["flarm-id"]) && $_POST["flarm-id"] != "") {
    $flarmId = checkInput($_POST["flarm-id"]);
  } else {
    $flarmId = "---";
  }

  //Plane competition class
  if (isset($_POST["competition-class"]) && $_POST["competition-class"] != 0) {
    $competitionClass = checkInput($_POST["competition-class"]);
  } else {
    array_push($errorArray, $language[$l]["error-form-competition-class"]);
  }

  //Pilot accomodation
  if (isset($_POST["accomodation"]) && $_POST["accomodation"] != 0) {
    $accomodation = checkInput($_POST["accomodation"]);
  } else {
    array_push($errorArray, $language[$l]["error-form-accomodation"]);
  }

  //Other info
  if ($_POST["other-info"] != "") {
    $otherInfo = checkInput($_POST["other-info"]);
  } else {
    $otherInfo = "Ei muuta infoa";
  }

  //******* UPLOAD OF IGC FILES *********/
  if ($_FILES["flight-logger-1"]["error"] != 4 || $_FILES['flight-logger-1']['size'] != 0) {
    $target_dir = "igcfiles/";
    $target_file = basename($_FILES["flight-logger-1"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $fileSize = $_FILES["flight-logger-1"]["size"];

    $priority = 1;

    if ($fileType != 'igc') {
      array_push($errorArray, $language[$l]["error-form-igc-1"]);
    }

    if ($fileSize > 3000000) {
      array_push($errorArray, $language[$l]["error-form-file-1-to-big"]);
    }

    if (empty($errorArray)) {
      move_uploaded_file($_FILES['flight-logger-1']['tmp_name'], $target_dir . $competitionSign . "-" . $priority . "-" . $target_file);
      $logger1 = 1;
    }
  } else {
    $logger1 = 0;
  }

  if ($_FILES["flight-logger-2"]["error"] != 4 || $_FILES['flight-logger-2']['size'] != 0) {
    $target_dir = "igcfiles/";
    $target_file = basename($_FILES["flight-logger-2"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $fileSize = $_FILES["flight-logger-2"]["size"];

    $priority = 2;

    if ($fileType != 'igc') {
      array_push($errorArray, $language[$l]["error-form-igc-2"]);
    }

    if ($fileSize > 3000000) {
      array_push($errorArray, $language[$l]["error-form-file-2-to-big"]);
    }

    if (empty($errorArray)) {
      move_uploaded_file($_FILES['flight-logger-2']['tmp_name'], $target_dir . $competitionSign . "-" . $priority . "-" . $target_file);
      $logger2 = 1;
    }
  } else {
    $logger2 = 0;
  }
  //******* UPLOAD OF IGC FILES END *********/

  if (empty($errorArray)) {
    //ENTRY SUCCESS
    $success = "";
    $et = new DateTime();
    $entryTime = $et->format("Y-m-d H:i:s");
    $np = new Pilots();
    $newPilot = $np->newPilot($competitionId, $firstName, $lastName, $phone, $email, $club, $country, $competitionClass, $accomodation, $otherInfo, $glider, $register, $competitionSign, $wingspan, $winglets, $engine, $flarmId, $logger1, $logger2, $pilotLinkId, $entryTime);

    if ($newPilot == 1) {
      header("location:" . $confirmationUrl);
    } else if ($newPilot == 0) {
      $success = "<div class='alert alert-danger'>Jokin meni pieleen... :( Yritä hetken kuluttua uudelleen.</div>";
    }
  } else {
    $warnings = "<div class='alert alert-danger text-center mx-3'><h6>" . $language[$l]["error-form-header"] . "</h6>";
    foreach ($errorArray as $key => $value) {
      $warnings .= $value . "<br>";
    }
    $warnings .= "</div>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $language[$l]["pilot-info-title"]; ?>
  </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https: //kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
</head>

<body class="bg-secondary">
  <div class="container py-5 bg-light">
    <h3 class="text-center"><?php echo $competitionInfo[0]["competition_name"]; ?>
    </h3>
    <p class="lead text-center">Pilotin tiedot</p>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>/