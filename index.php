<?php
require "functions.php";

//VARIABLES
$competitionId = 2;
$c = new Competitions();
$cp = new Pilots();
$pilotCountry = 0;
//SELECTING COMPETITION INFO
$competitionInfo = $c->selectCompetitionInfo($competitionId);

//LOCAL TIMEZONE
date_default_timezone_set("Europe/Helsinki");

//SELECT COMPETITON PILOTS (ONLY NEEDDE IN THIS SCRIPT)
$pilots = $cp->selectCompetitionPilots($competitionId);

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
$competitionDates = $cs->format("j.n.") . "-" . $ce->format("j.n.Y");

//LINKS
$host = $competitionInfo[0]["competition_web_host"];
$path = $competitionInfo[0]["competition_folder"];
$confirmationUrl = $host . $path . "confirmation.php";
$entriesUrl = $host . $path . "entries.php";
$emailImage = $host . $path . "images/" . $competitionInfo[0]['competition_logo_image']; //ATTENTION! .PNG FILE EXTENSION

//HEADER INFO
$competitionName = $competitionInfo[0]["competition_name"];
$competitionLocation = $competitionInfo[0]["competition_location"];

//HEADER STYLING
//Background image size 1000x300
$headerImage = "background-image: url(images/" . $competitionInfo[0]['competition_header_image'] . ")";
$confirmationHeaderImage = "images/" . $competitionInfo[0]['competition_logo_image'] . ")"; //ATTENTION! .PNG FILE EXTENSION
$entriesHeaderImage = "background-image: url(images/" . $competitionInfo[0]['competition_header_image'] . ")";
$competitionNameTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:10%;";
$competitionDateTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:20%;";
$competitionLocationTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:30%;";

//COMPETITION LINKS
if ($l == 0) {
  $linkWebSite = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_web_site'] . "' target='_blank'>Verkkosivut</a>";
  $linkSoaringSpot = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_soaringspot'] . "' target='_blank'>SoaringSpot</a>";
  $linkEntries = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $entriesUrl . "' target='_blank'>Ilmoittautuneet</a>";
} else if ($l == 1) {
  $linkWebSite = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_web_site'] . "' target='_blank'>Website</a>";
  $linkSoaringSpot = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_soaringspot'] . "' target='_blank'>SoaringSpot</a>";
  $linkEntries = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $entriesUrl . "' target='_blank'>Entries</a>";
}

//HANDLING UPDATE FORM INPUTS
//Error array for form inputs
$errorArray = [];
if (isset($_POST["submit"])) {

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
      case "oik":
        $club = "Oulun Ilmailukerho";
        break;
      case "hyik":
        $club = "Hyvinkään Ilmailukerho";
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
      $pilotCountry = checkInput($_POST["pilot-country"]);
    } else {
      array_push($errorArray, $language[$l]["error-form-country"]);
    }
  } else if ($competitionInfo[0]["competition_international"] == 0) {
    $pilotCountry = 1;
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
    $pilotLinkId = uniqid($competitionInfo[0]["competition_uniq_id"]. strtolower($competitionSign));
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
    $logger1 = "---";
  }

  if ($_FILES["flight-logger-2"]["error"] != 4 || $_FILES['flight-logger-2']['size'] != 0) {
    $target_dir = "igcfiles/";
    $target_file = basename($_FILES["flight-logger-2"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $fileSize = $_FILES["flight-logger-2"]["size"];
    $logger2 =  basename($_FILES["flight-logger-2"]["name"]);

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
    $logger2 = "---";
  }
  //******* UPLOAD OF IGC FILES END *********/

  if (empty($errorArray)) {
    $pilotLinkUpdate = $host . $path . "pilot_update.php?pilot_id=" . $pilotLinkId;
    //ENTRY SUCCESS
    $success = "";
    $et = new DateTime();
    $entryTime = $et->format("Y-m-d H:i:s");
    $np = new Pilots();
    $newPilot = $np->newPilot($competitionId, $firstName, $lastName, $phone, $email, $club, $pilotCountry, $competitionClass, $accomodation, $otherInfo, $glider, $register, $competitionSign, $wingspan, $winglets, $engine, $flarmId, $logger1, $logger2, $pilotLinkId, $entryTime, $entryTime);
    sendConfirmationMail($competitionId, $competitionName, $pilotLinkId, $pilotLinkUpdate, $entriesUrl, $emailImage, $firstName, $lastName, $email);
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
} //End of submit

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $competitionName; ?>
  </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
  <script src="main.js"></script>
  <script>
    //Prevents resending form when page is refreshed
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</head>

<body class="bg-dark">
  <div class="container bg-light pb-3 pt-2 mt-2">
    <header class="m-0 p-0">
      <div style="<?php echo $headerImage; ?>" id="header">
        <h3 style="<?php echo $competitionNameTextStyle; ?>"><?php echo $competitionName; ?></h3>
        <h3 style="<?php echo $competitionDateTextStyle; ?>"><?php echo $competitionDates; ?></h3>
        <h3 style="<?php echo $competitionLocationTextStyle; ?>"><?php echo $competitionLocation; ?></h3>
      </div>
    </header>
    <?php if ($l == 0 && $competitionInfo[0]["competition_info_visible"] == 1) {
      echo "<div class='competition-info alert alert-secondary p-2 p-md-4 mt-3 text-dark'>";
      echo "<h5 class='mt-3'>KILPAILUINFO</h5>" . nl2br($competitionInfo[0]["competition_info_fin"]);
      echo "</div>";
    } else if ($l == 1 && $competitionInfo[0]["competition_info_visible"] == 1) {
      echo "<div class='competition-info alert alert-secondary p-2 p-md-4 mt-3 text-dark'>";
      echo "<h5 class='mt-3'>COMPETITION INFO</h5>" . nl2br($competitionInfo[0]["competition_info_eng"]);
      echo "</div>";
    }
    //END OF COMPETITION INFO
    if ($l == 0) {
      echo "<div class='competition-links alert alert-secondary p-2 p-md-4 mt-3 text-dark'>
        <h5 class='mt-3 mb-3'>LINKKEJÄ</h5>
        <div class='row justify-content-center justify-content-md-start'>
        <div class='col-8 col-md-3'>" . $linkWebSite . "</div>
        <div class='col-8 col-md-3'>" . $linkEntries . "</div>
        <div class='col-8 col-md-3'>" . $linkSoaringSpot . "</div>
        </div>
        </div>";
    } else if ($l == 1)
      echo "<div class='competition-links alert alert-secondary p-2 p-md-4 mt-3 text-dark'>
        <h5 class='mt-3'>LINKS</h5>
        <div class='row justify-content-center justify-content-md-start'>
        <div class='col-8 col-md-3'>" . $linkWebSite . "</div>
        <div class='col-8 col-md-3'>" . $linkEntries . "</div>
        <div class='col-8 col-md-3'>" . $linkSoaringSpot . "</div>
        </div>
        </div>";
    ?>
    <div class="enrollment-form border p-4 p-md-3">
      <?php if (isset($warnings)) {
        echo $warnings;
      } ?>
      <h4><?php echo $language[$l]["header"]; ?></h4>
      <small class="text-danger"><?php echo $language[$l]["mandatory-fields"]; ?>
      </small>
      <form name="enrollment" id="enrollment-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <!--===== PILOT INFO =====-->
        <fieldset>
          <legend><?php echo $language[$l]["fieldset-1"]; ?></legend>
          <div class="row">
            <div class="col-12 col-md-2 mb-3 b-md-0">
              <label for="pilot-first-name"><?php echo $language[$l]["label-pilot-first-name"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" type="text" name="pilot-first-name" id="pilot-first-name" value="<?php if (isset($firstName)) {
                                                                                                              echo $firstName;
                                                                                                            } ?>" required>
            </div>
            <div class="col-12 col-md-2 mb-3 b-md-0">
              <label for="pilot-last-name"><?php echo $language[$l]["label-pilot-last-name"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" type="text" name="pilot-last-name" id="pilot-last-name" value="<?php if (isset($lastName)) {
                                                                                                            echo $lastName;
                                                                                                          } ?>" required>
            </div>
            <div class="col-12 col-md-2 mb-3 b-md-0">
              <label for="pilot-phone"><?php echo $language[$l]["label-pilot-phone"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" type="text" name="pilot-phone" id="pilot-phone" value="<?php if (isset($phone)) {
                                                                                                    echo $phone;
                                                                                                  } ?>" required>
            </div>
            <div class="col-12 col-md-3 mb-3 b-md-0">
              <label for="pilot-email"><?php echo $language[$l]["label-pilot-email"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" type="email" name="pilot-email" id="pilot-email" value="<?php if (isset($email)) {
                                                                                                    echo $email;
                                                                                                  } ?>" required>
            </div>
            <div class="col-12 col-md-3 mb-3 b-md-0">
              <label for="pilot-club"><?php echo $language[$l]["label-pilot-club"]; ?></label>
              <input class="form-control" type="text" name="pilot-club" id="pilot-club" value="<?php if (isset($club)) {
                                                                                                  echo $club;
                                                                                                } ?>">
            </div>
          </div>
          <!--===== COMPETITION INTERNATIONAL =====-->
          <?php if ($competitionInfo[0]["competition_international"] == 1) { ?>
            <div class="row">
              <label for="pilot-country"><?php echo $language[$l]["label-pilot-country"] . "<span class='text-danger'> *</span>"; ?></label>
              <div class="col-12 col-md-2 mb-3 mb-md-0">
                <select class="form-select" name="pilot-country" id="pilot-country">
                  <option value="0" selected disabled><?php echo $language[$l]["select-option-country"]; ?></option>
                  <?php
                  if ($l == 0) {
                    foreach ($pilotsCountries as $country) {

                      if ($country["country_id"] == $pilotCountry) {
                        echo "<option value='" . $country['country_id'] . "' selected>" . $country["country_name_fin"] . "</option>";
                      } else if ($country["country_id"] != $pilotCountry) {
                        echo "<option value=" . $country['country_id'] . ">" . $country["country_name_fin"] . "</option>";
                      }
                    }
                  } else if ($l == 1) {
                    foreach ($pilotsCountries as $country) {

                      if ($country["country_id"] == $pilotCountry) {
                        echo "<option value='" . $country['country_id'] . "' selected>" . $country["country_name_eng"] . "</option>";
                      } else if ($country["country_id"] != $pilotCountry) {
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
            <div class="col-12 col-md-2 mb-3 mb-md-0">
              <label for="plane-type"><?php echo $language[$l]["label-plane-type"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" type="text" name="plane-type" id="plane-type" value="<?php if (isset($glider)) {
                                                                                                  echo $glider;
                                                                                                } ?>" required>
            </div>
            <div class="col-12 col-md-2 mb-3 mb-md-0">
              <label for="plane-register"><?php echo $language[$l]["label-plane-register"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" register="text" name="plane-register" id="plane-register" value="<?php if (isset($register)) {
                                                                                                              echo $register;
                                                                                                            } ?>" required>
            </div>
            <div class="col-12 col-md-2 mb-3 mb-md-0">
              <label for="plane-competition-sign"><?php echo $language[$l]["label-plane-competition-sign"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" competition-sign="text" name="plane-competition-sign" id="plane-competition-sign" maxlength="3" value="<?php if (isset($competitionSign)) {
                                                                                                                                      echo $competitionSign;
                                                                                                                                    } ?>" required>
            </div>
            <div class="col-12 col-md-2 mb-3 mb-md-0">
              <label for="plane-wingspan"><?php echo $language[$l]["label-plane-wingspan"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" wingspan="text" name="plane-wingspan" id="plane-wingspan" value="<?php if (isset($wingspan)) {
                                                                                                              echo $wingspan;
                                                                                                            } ?>" required>
            </div>
            <div class="col-12 col-md-2 mb-3 mb-md-0">
              <label for="plane-winglets"><?php echo $language[$l]["label-plane-winglets"] . "<span class='text-danger'> *</span>"; ?></label>
              <select class="form-select" name="plane-winglets" id="plane-winglets">
                <option value="0" selected disabled><?php echo $language[$l]["select-option-winglets-1"]; ?></option>
                <option value="1" <?php if (isset($winglets) && $winglets == 1) {
                                    echo "selected";
                                  } ?>><?php echo $language[$l]["select-option-winglets-2"]; ?></option>
                <option value="2" <?php if (isset($winglets) && $winglets == 2) {
                                    echo "selected";
                                  } ?>><?php echo $language[$l]["select-option-winglets-3"]; ?></option>
              </select>
            </div>
            <div class="col-12 col-md-2 mb-3 mb-md-0">
              <label for="plane-engine"><?php echo $language[$l]["label-plane-engine"] . "<span class='text-danger'> *</span>"; ?></label>
              <select class="form-select" name="plane-engine" id="plane-engine">
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
            <div class="col-12 col-md-2 mb-3 mb-md-0">
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
            <div class="col-12 col-md-2 mb-3 mb-md-0">
              <label for="competition-class"><?php echo $language[$l]["label-competition-class"] . "<span class='text-danger'> *</span>"; ?></label>
              <!--===== Classes selected from database =====-->
              <select class="form-select" name="competition-class" id="competition-class" required>
                <?php
                if ($l == 0) {
                  echo "<option value = '0' disabled selected>Valitse luokka</option>";
                  foreach ($competitionClasses as $class) {
                    if (isset($competitionClass) && $competitionClass == $class['class_id']) {
                      echo "<option value='" . $class['class_id'] . "' selected>" . $class['class_name_fin'] . "</option>";
                    } else {
                      echo "<option value='" . $class['class_id'] . "'>" . $class['class_name_fin'] . "</option>";
                    }
                  }
                } else if ($l == 1) {
                  echo "<option value = '0' disabled selected>Choose class</option>";
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
            <div class="col-12 col-md-3 mb-3 mb-md-0">
              <label for="flight-logger-1" class="form-label"><?php echo $language[$l]["label-logger-1"]; ?></label>
              <input class="form-control" type="file" id="flight-logger-1" name="flight-logger-1">
            </div>
            <div class="col-12 col-md-3 mb-3 mb-md-0">
              <label for="flight-logger-2" class="form-label"><?php echo $language[$l]["label-logger-2"]; ?></label>
              <input class="form-control" type="file" id="flight-logger-2" name="flight-logger-2">
            </div>
            <div class="col-12 col-md-4 mb-3 mb-md-0">
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
            <div class="col-12 col-md-3 mb-3 mb-md-0">
              <label for="accomodation"><?php echo $language[$l]["label-accomodation"] . "<span class='text-danger'> *</span>"; ?></label>
              <select class="form-select" name="accomodation" id="accomodation" required>
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
            <div class="col-12 col-md-9 mb-3 mb-md-0">
              <label for="other-info"><?php echo $language[$l]["label-other-info"]; ?></label>
              <small class="d-block">(<?php echo $language[$l]['placeholder-info']; ?>)</small>
              <textarea class="w-100 form-control" name="other-info" id="other-info" rows="10">
                <?php if (isset($otherInfo)) {
                  echo $otherInfo;
                } ?></textarea>
            </div>
          </div>
        </fieldset>
        <div class="row mt-5">
          <div class="col-12 col-md-4 offset-md-4">
            <button form="enrollment-form" class="btn btn-success w-100" name="submit" type="submit"><?php echo $language[$l]['button-enrollment-send']; ?></button>
          </div>
        </div>
      </form>
    </div>
    <div class="footer py-4">
      <h5 class="text-center"><?php echo $language[$l]["footer-header"]; ?></h5>
      <div class="row p-4 p-md-0">
        <div class="col-6 col-md-3 offset-md-4">
          <h6><?php echo $competitionInfo[0]["competition_organiser"] . "<br>"; ?></h6>
          <?php echo $competitionInfo[0]["competition_organiser_contact_name"] . "<br>";
          echo $competitionInfo[0]["competition_organiser_phone"] . "<br>";
          echo $competitionInfo[0]["competition_organiser_email"];
          ?>
        </div>
        <div class="col-6 col-md-3">
          <h6><?php echo $language[$l]["footer-competition-director"]; ?></h6>
          <?php echo $competitionInfo[0]["competition_director"] . "<br>";
          echo $competitionInfo[0]["competition_director_phone"] . "<br>";
          echo $competitionInfo[0]["competition_director_email"];
          ?>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>