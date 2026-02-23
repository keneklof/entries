<?php
include "variables.php";
include "language.php";
include "functions.php";

if (isset($_GET["pilot_id"])) {
  $pilotLinkId = checkInput($_GET["pilot_id"]);
}

//$competitionId = 1;
//$c = new Competitions();
//$competitionInfo = $c->selectCompetitionInfo($competitionId);

$ud = new Pilots();

$pilotInfo = $ud->selectSinglePilotInfo($competitionId, $pilotLinkId);

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
  <title><?php echo $language[$l]["pilot-update-title"]; ?>
  </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https: //kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
</head>

<body class="bg-secondary">
  <div class="container bg-light pb-3">
    <div class="update-form border p-5 p-md-3">
      <h3 class="text-center"><?php echo $competitionInfo[0]["competition_name"]; ?></h3>
      <h4 class="text-center"><?php echo $language[$l]["pilot-update-title"]; ?>
      <p class="lead text-center"><?php echo $pilotInfo[0]["pilot_first_name"]."&nbsp;".$pilotInfo[0]["pilot_last_name"]."&nbsp;(".$pilotInfo[0]["plane_competition_sign"].")"; ?></p>
      <?php if (isset($warnings)) {
        echo $warnings;
      } ?>
      <h4><?php echo $language[$l]["pilot-update-header"]; ?></h4>
      <form name="enrollment" id="update-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <!--===== PILOT INFO =====-->
        <fieldset>
          <legend><?php echo $language[$l]["fieldset-1"]; ?></legend>
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
                <select class="form-control" name="pilot-country" id="pilot-country">
                  <option value="0" selected><?php echo $language[$l]["select-option-country"]; ?></option>
                  <?php
                  if ($l == 0) {
                    foreach ($pilotsCountries as $country) {

                      if ($country["country_id"] == $country) {
                        echo "<option value='" . $country['country_id'] . "' selected>" . $country["country_name_fin"] . "</option>";
                      } else if ($country["country_id"] != $country) {
                        echo "<option value=" . $country['country_id'] . ">" . $country["country_name_fin"] . "</option>";
                      }
                    }
                  } else if ($l == 1) {
                    foreach ($pilotsCountries as $country) {

                      if ($country["country_id"] == $country) {
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
              <input class="form-control" type="text" name="plane-type" id="plane-type" value="<?php if (isset($glider)) {
                                                                                                  echo $glider;
                                                                                                } ?>" required>
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-register"><?php echo $language[$l]["label-plane-register"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" register="text" name="plane-register" id="plane-register" value="<?php if (isset($register)) {
                                                                                                              echo $register;
                                                                                                            } ?>" required>
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-competition-sign"><?php echo $language[$l]["label-plane-competition-sign"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" competition-sign="text" name="plane-competition-sign" id="plane-competition-sign" value="<?php if (isset($competitionSign)) {
                                                                                                                                      echo $competitionSign;
                                                                                                                                    } ?>" required>
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-wingspan"><?php echo $language[$l]["label-plane-wingspan"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" wingspan="text" name="plane-wingspan" id="plane-wingspan" value="<?php if (isset($wingspan)) {
                                                                                                              echo $wingspan;
                                                                                                            } ?>" required>
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-winglets"><?php echo $language[$l]["label-plane-winglets"] . "<span class='text-danger'> *</span>"; ?></label>
              <select class="form-control" name="plane-winglets" id="plane-winglets">
                <option value="0" selected disabled><?php echo $language[$l]["select-option-winglets-1"]; ?></option>
                <option value="1" <?php if (isset($winglets) && $winglets == 1) {
                                    echo "selected";
                                  } ?>><?php echo $language[$l]["select-option-winglets-2"]; ?></option>
                <option value="2" <?php if (isset($winglets) && $winglets == 2) {
                                    echo "selected";
                                  } ?>><?php echo $language[$l]["select-option-winglets-3"]; ?></option>
              </select>
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-engine"><?php echo $language[$l]["label-plane-engine"] . "<span class='text-danger'> *</span>"; ?></label>
              <select class="form-control" name="plane-engine" id="plane-engine">
                <option value="0" selected disabled><?php echo $language[$l]["select-option-engine-1"]; ?></option>
                <option value='1' <?php if (isset($engine) && $engine == 1) {
                                    echo "selected";
                                  } ?>><?php echo $language[$l]['select-option-engine-2']; ?></option>
                <option value="2" <?php if (isset($engine) && $engine == 2) {
                                    echo "selected";
                                  } ?>><?php echo $language[$l]["select-option-engine-3"]; ?></option>
                <option value="3" <?php if (isset($engine) && $engine == 3) {
                                    echo "selected";
                                  } ?>><?php echo $language[$l]["select-option-engine-4"]; ?></option>
              </select>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-12 col-md-2">
              <label for="flarm-id">Flarm ID</label>
              <input class="form-control" type="text" name="flarm-id" id="flarm-id" value="<?php if (isset($flarmId)) {
                                                                                              echo $flarmId;
                                                                                            } ?>">
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
              <select class="form-control" name="competition-class" id="competition-class" required>
                <?php
                if ($l == 0) {
                  echo "<option value = '0'>Valitse luokka</option>";
                  foreach ($competitionClasses as $class) {
                    if (isset($competitionClass) && $competitionClass == $class['class_id']) {
                      echo "<option value='" . $class['class_id'] . "' selected>" . $class['class_name_fin'] . "</option>";
                    } else {
                      echo "<option value='" . $class['class_id'] . "'>" . $class['class_name_fin'] . "</option>";
                    }
                  }
                } else if ($l == 1) {
                  echo "<option value = '0'>Choose class</option>";
                  foreach ($competitionClasses as $class) {
                    if (isset($competitionClass) && $competitionClass == $class['class_id']) {
                      echo "<option value='" . $class['class_id'] . "' selected>" . $class['class_name_eng'] . "</option>";
                    } else {
                      echo "<option value='" . $class['class_id'] . "'>" . $class['class_name_eng'] . "</option>";
                    }
                  }
                }
                ?>
              </select>
            </div>
            <div class="col-12 col-md-3">
              <label for="flight-logger-1" class="form-label"><?php echo $language[$l]["label-logger-1"]; ?></label>
              <input class="form-control" type="file" id="flight-logger-1" name="flight-logger-1">
            </div>
            <div class="col-12 col-md-3">
              <label for="flight-logger-2" class="form-label"><?php echo $language[$l]["label-logger-2"]; ?></label>
              <input class="form-control" type="file" id="flight-logger-2" name="flight-logger-2">
            </div>
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
              <select class="form-control" name="accomodation" id="accomodation" required>
                <?php
                $selected1 = '';
                $selected2 = '';
                $selected3 = '';
                $selected4 = '';
                $selected5 = '';
                $selected6 = '';
                if (isset($accomodation) && $accomodation == 1) {
                  $selected1 = 'selected';
                }
                if (isset($accomodation) && $accomodation == 2) {
                  $selected2 = 'selected';
                }
                if (isset($accomodation) && $accomodation == 3) {
                  $selected3 = 'selected';
                }
                if (isset($accomodation) && $accomodation == 4) {
                  $selected4 = 'selected';
                }
                if (isset($accomodation) && $accomodation == 5) {
                  $selected5 = 'selected';
                }
                if (isset($accomodation) && $accomodation == 6) {
                  $selected6 = 'selected';
                }

                echo "<option value='0' selected disabled>" . $language[$l]['choose-accomodation'] . "</option>";
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
              <textarea class="w-100 form-control" name="other-info" id="other-info" rows="10" placeholder="<?php echo $language[$l]['placeholder-info']; ?>"></textarea>
            </div>
          </div>
        </fieldset>
        <div class="row mt-5">
          <div class="col-12 col-md-4 offset-md-4">
            <button form="update-form" class="btn btn-success w-100" name="update" type="submit"><?php echo $language[$l]['button-enrollment-send']; ?></button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
