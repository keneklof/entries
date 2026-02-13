<?php
include "autoloader.php";
include "language.php";
include "functions.php";

//COMPETION VARIABLES
$competitionId = 1;
//DATABASE SELECTIONS
//Select competition info
$c = new Competitions();
$competitionInfo = $c->selectCompetitionInfo($competitionId);
//Select competition classes
$competitonClasses = $c->selectCompetitonClasses($competitionId);

//LANGUAGE (0 = FINNISH, 1 = ENGLISH)
if ($competitionInfo[0]["competition_language"] == "FIN") {
  $l = 0;
} else if ($competitionInfo[0]["competition_language"] == "ENG") {
  $l = 1;
}

$competitionTitle = $competitionInfo[0]["competition_name"];
$competitionLocation = $competitionInfo[0]["competition_location"];

//CREATING COMPETITION DATES
$cs = new DateTime($competitionInfo[0]["competition_start"]);
$ce = new DateTime($competitionInfo[0]["competition_end"]);
$competitionDates = $cs->format("d.m.") . "-" . $ce->format("d.m.Y");

//HEADER STYLING
//Background image size 1000x300
$headerImage = "background-image: url('images/header-image.jpg')";
$competitionNameTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:10%;";
$competitionDateTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:20%;";
$competitionLocationTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:30%;";

//COMPETITION LINKS
if ($l == 0) {
  $linkWebSite = "<a class='btn btn-outline-primary w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_web_site'] . "' target='_blank'>Websivut</a>";
  $linkSoaringSpot = "<a class='btn btn-outline-primary w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_soaringspot'] . "' target='_blank'>SoaringSpot</a>";
  $linkEnrolled = "<a class='btn btn-outline-primary w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_enrolled'] . "' target='_blank'>Ilmoittautuneet</a>";
} else if ($l == 1) {
  $linkWebSite = "<a class='btn btn-outline-primary w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_web_site'] . "' target='_blank'>Website</a>";
  $linkSoaringSpot = "<a class='btn btn-outline-primary w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_soaringspot'] . "' target='_blank'>SoaringSpot</a>";
  $linkEnrolled = "<a class='btn btn-outline-primary w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_enrolled'] . "' target='_blank'>Enrolled</a>";
}

//HANDLING FORM INPUTS
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
  if (isset($_POST["pilot-club"])) {
    $club = checkInput($_POST["pilot-club"]);
  } else {
    $club = "Ei kerhoa";
  }

} //End of submit

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $competitionTitle; ?>
  </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
  <script src="main.js"></script>
</head>

<body class="bg-dark">
  <div class="container bg-light pb-3 pt-2 mt-2">
    <header class="m-0 p-0">
      <div style="<?php echo $headerImage; ?>" id="header">
        <h3 style="<?php echo $competitionNameTextStyle; ?>"><?php echo $competitionTitle; ?></h3>
        <h3 style="<?php echo $competitionDateTextStyle; ?>"><?php echo $competitionDates; ?></h3>
        <h3 style="<?php echo $competitionLocationTextStyle; ?>"><?php echo $competitionLocation; ?></h3>
      </div>
    </header>
    <div class="competition-info alert alert-secondary p-2 p-md-4 mt-3 text-dark">
      <?php if ($l == 0) {
        echo "<h5 class='mt-3'>KILPAILUINFO</h5>" . nl2br($competitionInfo[0]["competition_info_fin"]) .
          "<h5 class='mt-3'>LINKKEJÄ</h5>
        <div class='row justify-content-center justify-content-md-start'>
        <div class='col-8 col-md-3'>" . $linkWebSite . "</div>
        <div class='col-8 col-md-3'>" . $linkEnrolled . "</div>
        <div class='col-8 col-md-3'>" . $linkSoaringSpot . "</div>
        </div>";
      } else if ($l == 1) {
        echo "<h5 class='mt-3'>COMPETITION INFO</h5>" . nl2br($competitionInfo[0]["competition_info_eng"]) .
          "<h5 class='mt-3'>LINKS</h5>
        <div class='row justify-content-center justify-content-md-start'>
        <div class='col-8 col-md-3'>" . $linkWebSite . "</div>
        <div class='col-8 col-md-3'>" . $linkEnrolled . "</div>
        <div class='col-8 col-md-3'>" . $linkSoaringSpot . "</div>
        </div>";
      }
      ?>
    </div>
    <div class="enrollment-form border p-4 p-md-3">
      <h4><?php echo $language[$l]["header"]; ?></h4>
      <form name="enrollment" id="enrollment-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"> ´
        <!--===== PILOT INFO =====-->
        <fieldset>
          <legend><?php echo $language[$l]["fieldset-1"]; ?></legend>
          <input type="hidden" name="competition-id" value="<?php echo $competitionId; ?>">
          <div class="row">
            <div class="col-12 col-md-2">
              <label for="pilot-first-name"><?php echo $language[$l]["label-pilot-first-name"] . "<span class='text-danger'> *</span>"; ?></label>
              <input class="form-control" type="text" name="pilot-first-name" id="pilot-first-name">
            </div>
            <div class="col-12 col-md-2">
              <label for="pilot-last-name"><?php echo $language[$l]["label-pilot-last-name"]; ?></label>
              <input class="form-control" type="text" name="pilot-last-name" id="pilot-last-name">
            </div>
            <div class="col-12 col-md-2">
              <label for="pilot-phone"><?php echo $language[$l]["label-pilot-phone"]; ?></label>
              <input class="form-control" type="text" name="pilot-phone" id="pilot-phone">
            </div>
            <div class="col-12 col-md-3">
              <label for="pilot-email"><?php echo $language[$l]["label-pilot-email"]; ?></label>
              <input class="form-control" type="email" name="pilot-email" id="pilot-email">
            </div>
            <div class="col-12 col-md-3">
              <label for="pilot-club"><?php echo $language[$l]["label-pilot-club"]; ?></label>
              <input class="form-control" type="text" name="pilot-club" id="pilot-club">
            </div>
          </div>
        </fieldset>
        <hr>
        <!--===== PLANE INFO =====-->
        <fieldset class="my-4">
          <legend><?php echo $language[$l]["fieldset-2"]; ?></legend>
          <div class="row">
            <div class="col-12 col-md-2">
              <label for="plane-type"><?php echo $language[$l]["label-plane-type"]; ?></label>
              <input class="form-control" type="text" name="plane-type" id="plane-type">
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-register"><?php echo $language[$l]["label-plane-register"]; ?></label>
              <input class="form-control" register="text" name="plane-register" id="plane-register">
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-competition-sign"><?php echo $language[$l]["label-plane-competition-sign"]; ?></label>
              <input class="form-control" competition-sign="text" name="plane-competition-sign" id="plane-competition-sign">
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-wingspan"><?php echo $language[$l]["label-plane-wingspan"]; ?></label>
              <input class="form-control" wingspan="text" name="plane-wingspan" id="plane-wingspan">
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-winglets"><?php echo $language[$l]["label-plane-winglets"]; ?></label>
              <select class="form-control" name="plane-winglets" id="plane-winglets">
                <option value="0" selected disabled><?php echo $language[$l]["select-option-winglets-1"]; ?></option>
                <option value="1"><?php echo $language[$l]["select-option-winglets-2"]; ?></option>
                <option value="2"><?php echo $language[$l]["select-option-winglets-3"]; ?></option>
              </select>
            </div>
            <div class="col-12 col-md-2">
              <label for="plane-engine"><?php echo $language[$l]["label-plane-engine"]; ?></label>
              <select class="form-control" name="plane-engine" id="plane-engine">
                <option value="0" selected disabled><?php echo $language[$l]["select-option-engine-1"]; ?></option>
                <option value="1"><?php echo $language[$l]["select-option-engine-2"]; ?></option>
                <option value="2"><?php echo $language[$l]["select-option-engine-3"]; ?></option>
                <option value="3"><?php echo $language[$l]["select-option-engine-4"]; ?></option>
              </select>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-12 col-md-2">
              <label for="flarm-id">Flarm ID</label>
              <input class="form-control" type="text" name="flarm-id" id="flarm-id">
            </div>
          </div>
        </fieldset>
        <hr>
        <!--===== COMPETITION CLASS AND FLIGHT LOGGERS =====-->
        <fieldset class="my-4">
          <legend><?php echo $language[$l]["fieldset-3"]; ?></legend>
          <div class="row align-items-center">
            <div class="col-12 col-md-2">
              <label for="competition-class"><?php echo $language[$l]["label-competition-class"]; ?></label>
              <!--===== Classes seleted from database =====-->
              <select class="form-control" name="competition-class" id="competition-class">
                <?php
                if ($l == 0) {
                  echo "<option value = '0'>Valitse luokka</option>";
                  foreach ($competitonClasses as $class) {
                    echo "<option value='" . $class['class_id'] . "'>" . $class['class_name_fin'] . "</option>";
                  }
                } else if ($l == 1) {
                  echo "<option value = '0'>Choose class</option>";
                  foreach ($competitonClasses as $class) {
                    echo "<option value='" . $class['class_id'] . "'>" . $class['class_name_eng'] . "</option>";
                  }
                }
                ?>
              </select>
            </div>
            <div class="col-12 col-md-3">
              <label for="flight-logger-1" class="form-label"><?php echo $language[$l]["label-logger-1"]; ?></label>
              <input class="form-control" type="file" id="flight-logger-1" name="flight-logger-1" required>
            </div>
            <div class="col-12 col-md-3">
              <label for="flight-logger-2" class="form-label"><?php echo $language[$l]["label-logger-2"]; ?></label>
              <input class="form-control" type="file" id="flight-logger-2" name="flight-logger-2" required>
            </div>
            <div class="col-12 col-md-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="checkbox-logger-files" name="checkbox-logger-files" value="" onchange="flightLoggerOne()">
                <label class="form-check-label text-danger"><?php echo $language[$l]["label-checkbox-logger-files"]; ?></label>
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
              <label for="accomodation"><?php echo $language[$l]["label-accomodation"]; ?></label>
              <select class="form-control" name="accomodation" id="accomodation">
                <?php
                echo "<option value='0' selected disabled>" . $language[$l]['choose-accomodation'] . "</option>";
                echo "<option value='1'>" . $language[$l]['accomodation-motel'] . "</option>";
                echo "<option value='2'>" . $language[$l]['accomodation-season'] . "</option>";
                echo "<option value='3'>" . $language[$l]['accomodation-week'] . "</option>";
                echo "<option value='4'>" . $language[$l]['accomodation-tent'] . "</option>";
                echo "<option value='5'>" . $language[$l]['accomodation-no'] . "</option>";
                echo "<option value='6'>" . $language[$l]['accomodation-cns'] . "</option>";
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
            <button form="enrollment-form" class="btn btn-primary w-100" type="submit"><?php echo $language[$l]['button-enrollment-send']; ?></button>
          </div>
        </div>
      </form>
    </div>
    <div class="footer bg-secondary">
      <!--===== FOOTER CONTENT =====-->
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>