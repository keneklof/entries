<?php
include "language.php";
require "functions.php";
require "competition_id.php";

//VARIABLES
$cp = new Pilots();
$c = new Competitions();
$competitionInfo = $c->selectCompetitionInfo($competitionId);

if (isset($_GET["pilot_id"])) {
  $pilotLinkId = checkInput($_GET["pilot_id"]);
  $ud = new Pilots();
  $pilotInfo = $ud->selectSinglePilotInfo($competitionId, $pilotLinkId);
}

//CHECKING IF PILOT DOES NOT EXIST
//TODO!!

//LOCAL TIMEZONE
date_default_timezone_set("Europe/Helsinki");

//SELECT COMPETITION LANGUAGE
$competitionLanguage = $competitionInfo[0]["competition_language"];
if ($competitionLanguage == "FIN") {
  $l = 0;
} else if ($competitionLanguage == "ENG") {
  $l = 1;
}

//SELECT PILOTS COUNTRIES
$pilotsCountries = $c->selecPilotsCountries($competitionId);

//SELECT COMPETITION CLASSES
$competitionClasses = $c->selectCompetitionClasses($competitionId);

//CREATING COMPETITION DATES
$cs = new DateTime($competitionInfo[0]["competition_start"]);
$ce = new DateTime($competitionInfo[0]["competition_end"]);
$competitionDates = $cs->format("d.m.") . "-" . $ce->format("d.m.Y");

//LINKS
$host = $competitionInfo[0]["competition_web_host"];
$path = $competitionInfo[0]["competition_folder"];
$confirmationUrl = $host . $path . "confirmation.php";
$entriesUrl = $host . $path . "pilots/entries.php";
$emailImage = $host . $path . "images/competition-logo.png";

//HEADER INFO
$competitionName = $competitionInfo[0]["competition_name"];
$competitionLocation = $competitionInfo[0]["competition_location"];

//HEADER STYLING
//Background image size 1000x300
$headerImage = "background-image: url('images/header-image.jpg')";
$confirmationHeaderImage = "images/competition-logo.png"; //ATTENTION! .PNG FILE EXTENSION
$entriesHeaderImage = "background-image: url('../images/header-image.jpg')";
$competitionNameTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:10%;";
$competitionDateTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:20%;";
$competitionLocationTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:30%;";

//COMPETITION LINKS
if ($l == 0) {
  $linkWebSite = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_web_site'] . "' target='_blank'>Websivut</a>";
  $linkSoaringSpot = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_soaringspot'] . "' target='_blank'>SoaringSpot</a>";
  $linkEntries = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $entriesUrl . "' target='_blank'>Ilmoittautuneet</a>";
} else if ($l == 1) {
  $linkWebSite = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_web_site'] . "' target='_blank'>Website</a>";
  $linkSoaringSpot = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_soaringspot'] . "' target='_blank'>SoaringSpot</a>";
  $linkEntries = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $entriesUrl . "' target='_blank'>Entries</a>";
}

//HANDLING UPDATE FORM INPUTS
//Error array for form inputs
if (isset($_POST["update"])) {

  $errorArray = [];

  //Pilot link id
  if (isset($_POST["pilot-link-id"])) {
    $pilotLinkId = checkInput($_POST["pilot-link-id"]);
    $ud = new Pilots();
    $pilotInfo = $ud->selectSinglePilotInfo($competitionId, $pilotLinkId);
  }

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
    switch (strtolower($club)) {
      case "rac":
        $club = "Räyskälän Ilmailukerho";
        break;
      case "nil":
        $club = "Nuorisoilmailijat";
        break;
      case "kily":
        $club = "Kouvolan Seudun Ilmailuyhdistys";
        break;
      case "pik":
        $club = "Polyteknikkojen Ilmailuyhdistys";
        break;
      case "hyik":
        $club = "Hyvinkään Ilmailukerho";
        break;
      case "oik":
        $club = "Oulun Ilmailukerho";
        break;
      default:
        $club = $club;
        break;
    }
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
    $logger1 = basename($_FILES["flight-logger-1"]["name"]);

    $priority = 1;

    if ($fileType != 'igc') {
      array_push($errorArray, $language[$l]["error-form-igc-1"]);
    }

    if ($fileSize > 3000000) {
      array_push($errorArray, $language[$l]["error-form-file-1-to-big"]);
    }

    if (empty($errorArray)) {
      move_uploaded_file($_FILES['flight-logger-1']['tmp_name'], $target_dir . $competitionSign . "-" . $priority . "-" . $target_file);
    }
  } else {
    $logger1 = $pilotInfo[0]["plane_logger_one"];
  }

  if ($_FILES["flight-logger-2"]["error"] != 4 || $_FILES['flight-logger-2']['size'] != 0) {
    $target_dir = "igcfiles/";
    $target_file = basename($_FILES["flight-logger-2"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $fileSize = $_FILES["flight-logger-2"]["size"];
    $logger2 = basename($_FILES["flight-logger-2"]["name"]);

    $priority = 2;

    if ($fileType != 'igc') {
      array_push($errorArray, $language[$l]["error-form-igc-2"]);
    }

    if ($fileSize > 3000000) {
      array_push($errorArray, $language[$l]["error-form-file-2-to-big"]);
    }

    if (empty($errorArray)) {
      move_uploaded_file($_FILES['flight-logger-2']['tmp_name'], $target_dir . $competitionSign . "-" . $priority . "-" . $target_file);
    }
  } else {
    $logger2 = $pilotInfo[0]["plane_logger_two"];
  }
  //******* UPLOAD OF IGC FILES END *********/

  if (empty($errorArray)) {
    //ENTRY SUCCESS
    $et = new DateTime();
    $updateTime = $et->format("Y-m-d H:i:s");
    $np = new Pilots();
    $updatePilot = $np->updatePilot($competitionId, $pilotLinkId, $firstName, $lastName, $phone, $email, $club, $country, $competitionClass, $accomodation, $otherInfo, $glider, $register, $competitionSign, $wingspan, $winglets, $engine, $flarmId, $logger1, $logger2, $updateTime);

    if ($updatePilot == 1) {
      header("Location:" . $host . $path . "pilot_update.php?pilot_id=" . $pilotLinkId . "&update=1");
    } else if ($updatePilot == 0) {
      header("Location:" . $host . $path . "pilot_update.php?pilot_id=" . $pilotLinkId . "&update=0");
    } else if ($updatePilot == 2) {
      header("Location:" . $host . $path . "no_pilot.php");
    }
  }

  if (!empty($errorArray)) {
    $warnings = "<div class='alert alert-danger text-center mx-3'><h6>" . $language[$l]["error-form-header"] . "</h6>";
    foreach ($errorArray as $key => $value) {
      $warnings .= $value . "<br>";
    }
    $warnings .= "</div>";
  }
} //END OF BUTTON UPDATE

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $language[$l]["pilot-update-title"]." ".$pilotInfo[0]["pilot_first_name"] . "&nbsp;" . $pilotInfo[0]["pilot_last_name"]; ?>
  </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https: //kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
</head>

<body class="bg-secondary">
  <div class="container bg-light pb-3">
    <div class="update-form border p-5 p-md-3">
      <h3 class="text-center"><?php echo $competitionInfo[0]["competition_name"]; ?></h3>
      <h4 class="text-center"><?php echo $language[$l]["pilot-update-title"]; ?></h4>
      <p class="lead text-center mb-0"><?php echo $pilotInfo[0]["pilot_first_name"] . "&nbsp;" . $pilotInfo[0]["pilot_last_name"] . "&nbsp;(" . $pilotInfo[0]["plane_competition_sign"] . ")"; ?></p>
      <div class="text-center">
          <?php
          if ($pilotInfo[0]["pilot_entry_fee"] == 0) {
            echo "<p>".$language[$l]["pilot-update-entry-fee"]."&nbsp;<span class='text-danger'><strong>".$language[$l]["pilot-update-entry-fee-not-ok"]."</strong></span></p>";
          } else if ($pilotInfo[0]["pilot_entry_fee"] == 1) {
            echo "<p>".$language[$l]["pilot-update-entry-fee"]."&nbsp;<span class='text-success'><strong>".$language[$l]["pilot-update-entry-fee-ok"]."</strong></span></p>";
          }
          ?>
      </div>
      <?php if (isset($warnings)) {
        echo $warnings;
      } ?>
      <?php if (isset($_REQUEST["update"]) && $_REQUEST["update"] == 1) {
        echo "<div class='alert alert-success text-center'>" . $language[$l]['pilot-update-success'] . "</div>";
      } else if (isset($_REQUEST["update"]) && $_REQUEST["update"] == 0) {
        echo "<div class='alert alert-danger text-center'>" . $language[$l]['pilot-update-no-success'] . "</div>";
      }
      ?>

      <h4><?php echo $language[$l]["pilot-update-header"]; ?></h4>
      <form name="enrollment" id="update-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <!--===== PILOT INFO =====-->
        <fieldset>
          <legend><?php echo $language[$l]["fieldset-1"]; ?></legend>
          <input type="hidden" name="pilot-link-id" value="<?php echo $pilotLinkId; ?>">

          <div class="row">
            <div class="col-12 col-md-2">
              <label for="pilot-first-name"><?php echo $language[$l]["label-pilot-first-name"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" type="text" name="pilot-first-name" id="pilot-first-name" value="<?php
                                                                                                            echo $pilotInfo[0]["pilot_first_name"];
                                                                                                            ?>" required>
            </div>
            <div class="col-12 col-md-2">
              <label for="pilot-last-name"><?php echo $language[$l]["label-pilot-last-name"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" type="text" name="pilot-last-name" id="pilot-last-name" value="<?php
                                                                                                          echo $pilotInfo[0]["pilot_last_name"];
                                                                                                          ?>" required>
            </div>
            <div class="col-12 col-md-2">
              <label for="pilot-phone"><?php echo $language[$l]["label-pilot-phone"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" type="text" name="pilot-phone" id="pilot-phone" value="<?php
                                                                                                  echo $pilotInfo[0]["pilot_phone"];
                                                                                                  ?>" required>
            </div>
            <div class="col-12 col-md-3">
              <label for="pilot-email"><?php echo $language[$l]["label-pilot-email"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" type="email" name="pilot-email" id="pilot-email" value="<?php
                                                                                                  echo $pilotInfo[0]["pilot_email"];
                                                                                                  ?>" required>
            </div>
            <div class="col-12 col-md-3">
              <label for="pilot-club"><?php echo $language[$l]["label-pilot-club"]; ?></label>
              <input class="form-control" type="text" name="pilot-club" id="pilot-club" value="<?php
                                                                                                echo $pilotInfo[0]["pilot_club"];
                                                                                                ?>">
            </div>
          </div>
          <!--===== COMPETITION INTERNATIONAL =====-->
          <?php if ($competitionInfo[0]["competition_international"] == 1) { ?>
            <div class="row">
              <label for="pilot-country"><?php echo $language[$l]["label-pilot-country"] . "<span class='text-danger'> *</span>"; ?></label>
              <div class="col-12 col-md-2">
                <select class="form-select" name="pilot-country" id="pilot-country">
                  <?php
                  if ($l == 0) {
                    foreach ($pilotsCountries as $country) {

                      if ($pilotInfo[0]["pilot_country"] == $country["country_id"]) {
                        echo "<option value='" . $country['country_id'] . "' selected>" . $country["country_name_fin"] . "</option>";
                      } else if ($pilotInfo[0]["pilot_country"] != $country["country_id"]) {
                        echo "<option value=" . $country['country_id'] . ">" . $country["country_name_fin"] . "</option>";
                      }
                    }
                  } else if ($l == 1) {
                    foreach ($pilotsCountries as $country) {

                      if ($pilotInfo[0]["pilot_country"] == $country["country_id"]) {
                        echo "<option value='" . $country['country_id'] . "' selected>" . $country["country_name_eng"] . "</option>";
                      } else if ($country["country_id"] != $country) {
                        echo "<option value=" . $country['country_id'] . ">" . $country["country_name_eng"] . "</option>";
                      }
                    }
                  }
                  ?>
                </select>
              </div>
            </div>
          <?php } ?>

        </fieldset>
        <hr>
        <!--===== PLANE INFO =====-->
        <fieldset class="my-4">
          <legend><?php echo $language[$l]["fieldset-2"]; ?></legend>
          <div class="row">
            <div class="col-12 col-md-2">
              <label for="plane-type"><?php echo $language[$l]["label-plane-type"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" type="text" name="plane-type" id="plane-type" value="<?php
                                                                                                echo $pilotInfo[0]["plane_type"];
                                                                                                ?>" required>
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-register"><?php echo $language[$l]["label-plane-register"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" register="text" name="plane-register" id="plane-register" value="<?php
                                                                                                            echo $pilotInfo[0]["plane_register"];
                                                                                                            ?>" required>
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-competition-sign"><?php echo $language[$l]["label-plane-competition-sign"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" competition-sign="text" name="plane-competition-sign" id="plane-competition-sign" value="<?php
                                                                                                                                    echo $pilotInfo[0]["plane_competition_sign"];
                                                                                                                                    ?>" required>

            </div>
            <div class="col-12 col-md-2">
              <label for="plane-wingspan"><?php echo $language[$l]["label-plane-wingspan"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" wingspan="text" name="plane-wingspan" id="plane-wingspan" value="<?php
                                                                                                            echo $pilotInfo[0]["plane_wingspan"];
                                                                                                            ?>" required>
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-winglets"><?php echo $language[$l]["label-plane-winglets"] . "<span class='text-danger'> *</span>"; ?></label>
              <select class="form-select" name="plane-winglets" id="plane-winglets">
                <option value="1" <?php if ($pilotInfo[0]["plane_winglets"] == 1) {
                                    echo "selected";
                                  } ?>><?php echo $language[$l]["select-option-winglets-2"]; ?></option>
                <option value="2" <?php if ($pilotInfo[0]["plane_winglets"] == 2) {
                                    echo "selected";
                                  } ?>><?php echo $language[$l]["select-option-winglets-3"]; ?></option>
              </select>
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-engine"><?php echo $language[$l]["label-plane-engine"] . "<span class='text-danger'> *</span>"; ?></label>
              <select class="form-select" name="plane-engine" id="plane-engine">
                <option value='1' <?php if ($pilotInfo[0]["plane_engine"] == 1) {
                                    echo "selected";
                                  } ?>><?php echo $language[$l]['select-option-engine-2']; ?></option>
                <option value="2" <?php if ($pilotInfo[0]["plane_engine"] == 2) {
                                    echo "selected";
                                  } ?>><?php echo $language[$l]["select-option-engine-3"]; ?></option>
                <option value="3" <?php if ($pilotInfo[0]["plane_engine"] == 3) {
                                    echo "selected";
                                  } ?>><?php echo $language[$l]["select-option-engine-4"]; ?></option>
              </select>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-12 col-md-2">
              <label for="flarm-id">Flarm ID</label>
              <input class="form-control" type="text" name="flarm-id" id="flarm-id" value="<?php
                                                                                            echo $pilotInfo[0]["plane_flarm_id"];
                                                                                            ?>">
              <small class="text-danger"><?php if ($pilotInfo[0]["plane_flarm_id"] == "---") {
                                            echo $language[$l]["no-flarm-id"];
                                          } ?></small>

            </div>
          </div>
        </fieldset>
        <hr>
        <!--===== COMPETITION CLASS AND FLIGHT LOGGERS =====-->
        <fieldset class="my-4">
          <legend><?php echo $language[$l]["fieldset-3"]; ?></legend>
          <div class="row align-items-center">
            <div class="col-12 col-md-2">
              <label for="competition-class"><?php echo $language[$l]["label-competition-class"] . "<span class='text-danger'> *</span>"; ?></label>
              <!--===== Classes selected from database =====-->
              <select class="form-select" name="competition-class" id="competition-class" required>
                <?php
                if ($l == 0) {
                  foreach ($competitionClasses as $class) {
                    if ($pilotInfo[0]["plane_class"] == $class['class_id']) {
                      echo "<option value='" . $class['class_id'] . "' selected>" . $class['class_name_fin'] . "</option>";
                    } else {
                      echo "<option value='" . $class['class_id'] . "'>" . $class['class_name_fin'] . "</option>";
                    }
                  }
                } else if ($l == 1) {
                  foreach ($competitionClasses as $class) {
                    if ($pilotInfo[0]["plane_class"]  == $class['class_id']) {
                      echo "<option value='" . $class['class_id'] . "' selected>" . $class['class_name_eng'] . "</option>";
                    } else {
                      echo "<option value='" . $class['class_id'] . "'>" . $class['class_name_eng'] . "</option>";
                    }
                  }
                }
                ?>
              </select>
            </div>
            <?php if ($l == 1) { ?>
              <div class="col-12 col-md-3">
                <label for="flight-logger-1" class="form-label"><?php echo $language[$l]["label-logger-1"]; ?></label>
                <input class="form-control" type="file" id="flight-logger-1" name="flight-logger-1">
                <!--===== Logger info start =====-->
                <?php
                if ($pilotInfo[0]["plane_logger_one"] != "---") {
                  echo "<div class='text-success text-center mb-2 mb-md-0'><small>" . $pilotInfo[0]["plane_logger_one"] . "</small></div>";
                } else if ($pilotInfo[0]["plane_logger_one"] == "---") {
                  echo "<div class='text-danger text-center mb-2 mb-md-0'><small>No primary logger sent</small></div>";
                }
                ?>
              </div>
              <div class="col-12 col-md-3">
                <label for="flight-logger-2" class="form-label"><?php echo $language[$l]["label-logger-2"]; ?></label>
                <input class="form-control" type="file" id="flight-logger-2" name="flight-logger-2">
                <?php
                if ($pilotInfo[0]["plane_logger_two"] != "---") {
                  echo "<div class='text-success text-center mb-2 mb-md-0'><small>" . $pilotInfo[0]["plane_logger_two"] . "</small></div>";
                } else if ($pilotInfo[0]["plane_logger_two"] == "---") {
                  echo "<div class='text-danger text-center mb-2 mb-md-0'><small>No secondary logger sent</small></div>";
                }
                ?>
              </div>
            <?php } else if ($l == 0) { ?>
              <div class="col-12 col-md-3">
                <label for="flight-logger-1" class="form-label"><?php echo $language[$l]["label-logger-1"]; ?></label>
                <input class="form-control" type="file" id="flight-logger-1" name="flight-logger-1">
                <!--===== Logger info start =====-->
                <?php
                if ($pilotInfo[0]["plane_logger_one"] != "---") {
                  echo "<div class='text-success text-center mb-2 mb-md-0'><small>" . $pilotInfo[0]["plane_logger_one"] . "</small></div>";
                } else if ($pilotInfo[0]["plane_logger_one"] == "---") {
                  echo "<div class='text-danger text-center mb-2 mb-md-0'><small>Ei lähetettyä ykköstallenninta</small></div>";
                }
                ?>
              </div>
              <div class="col-12 col-md-3">
                <label for="flight-logger-2" class="form-label"><?php echo $language[$l]["label-logger-2"]; ?></label>
                <input class="form-control" type="file" id="flight-logger-2" name="flight-logger-2">
                <?php
                if ($pilotInfo[0]["plane_logger_two"] != "---") {
                  echo "<div class='text-success text-center mb-2 mb-md-0'><small>" . $pilotInfo[0]["plane_logger_two"] . "</small></div>";
                } else if ($pilotInfo[0]["plane_logger_two"] == "---") {
                  echo "<div class='text-danger text-center mb-2 mb-md-0'><small>Ei lähetettyä kakkostallenninta</small></div>";
                }
                ?>
              </div>
            <?php } ?>
            <!--===== Logger info end =====-->

            <div class="col-12 col-md-4">
              <div class="alert alert-secondary w-100">
                <small>
                  <?php if ($competitionInfo[0]["competition_language"] == 'FIN') {
                    echo $language[$l]["igc-info"];
                  } else if ($competitionInfo[0]["competition_language"] == 'ENG') {
                    echo $language[$l]["igc-info"];
                  } ?>
                </small>

              </div>
            </div>
          </div>
        </fieldset>
        <hr>
        <!--===== ACCOMODATION AND OTHER INFO =====-->
        <fieldset class="my-4">
          <legend><?php echo $language[$l]["fieldset-4"]; ?></legend>
          <div class="row">
            <div class="col-12 col-md-3">
              <label for="accomodation"><?php echo $language[$l]["label-accomodation"] . "<span class='text-danger'> *</span>"; ?></label>
              <select class="form-select" name="accomodation" id="accomodation" required>
                <?php
                $selected1 = '';
                $selected2 = '';
                $selected3 = '';
                $selected4 = '';
                $selected5 = '';
                $selected6 = '';
                if ($pilotInfo[0]["pilot_accomodation"] == 1) {
                  $selected1 = 'selected';
                }
                if ($pilotInfo[0]["pilot_accomodation"] == 2) {
                  $selected2 = 'selected';
                }
                if ($pilotInfo[0]["pilot_accomodation"] == 3) {
                  $selected3 = 'selected';
                }
                if ($pilotInfo[0]["pilot_accomodation"] == 4) {
                  $selected4 = 'selected';
                }
                if ($pilotInfo[0]["pilot_accomodation"] == 5) {
                  $selected5 = 'selected';
                }
                if ($pilotInfo[0]["pilot_accomodation"] == 6) {
                  $selected6 = 'selected';
                }

                echo "<option value='1' " . $selected1 . ">" . $language[$l]['accomodation-motel'] . "</option>";
                echo "<option value='2' " . $selected2 . ">" . $language[$l]['accomodation-season'] . "</option>";
                echo "<option value='3' " . $selected3 . ">" . $language[$l]['accomodation-week'] . "</option>";
                echo "<option value='4' " . $selected4 . ">" . $language[$l]['accomodation-tent'] . "</option>";
                echo "<option value='5' " . $selected5 . ">" . $language[$l]['accomodation-no'] . "</option>";
                echo "<option value='6' " . $selected6 . ">" . $language[$l]['accomodation-cns'] . "</option>";
                ?>
              </select>
            </div>
            <div class="col-12 col-md-9">
              <label for="other-info"><?php echo $language[$l]["label-other-info"]; ?></label>
              <small class="d-block">(<?php echo $language[$l]['placeholder-info']; ?>)</small>
              <textarea class="w-100 form-control" name="other-info" id="other-info" rows="10"><?php echo $pilotInfo[0]["pilot_other_info"]; ?></textarea>
            </div>
          </div>
        </fieldset>
      </form>
      <div class="row mt-5">
        <div class="col-12 col-md-3 offset-md-3 mb-3 mb-md-0">
          <button form="update-form" class="btn btn-success w-100" name="update" type="submit"><?php echo $language[$l]['button-enrollment-update']; ?></button>
        </div>
        <div class="col-12 col-md-3">
          <!-- Button trigger modal -->
          <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="<?php echo "#delete-" . $pilotInfo[0]["pilot_link_id"]; ?>">
            <?php echo $language[$l]['button-enrollment-delete']; ?>
          </button>
        </div>
      </div>
    </div>
  </div>
  <!--===== DELETE MODALS =====-->
  <!-- Modal -->
  <div class="modal fade" id="<?php echo "delete-" . $pilotInfo[0]["pilot_link_id"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">"
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo $language[$l]['button-enrollment-delete']; ?></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="delete_pilot.php" method="POST" id="delete-pilot">
          <div class="modal-body">
            <p><?php echo $language[$l]['button-enrollment-delete-text']; ?></p>
            <input type="hidden" name="delete-pilot" value="<?php echo $pilotInfo[0]["pilot_link_id"]; ?>">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $language[$l]['button-enrollment-cancel']; ?></button>
            <button for="delete-pilot" type="submit" class="btn btn-danger"><?php echo $language[$l]['button-enrollment-delete']; ?></button>
          </div>
        </form>

      </div>
    </div>
  </div>
  <div id="test">
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="main.js"></script>
</body>

</html>