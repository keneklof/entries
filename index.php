<?php
include "autoloader.php";
include "language.php";
include "functions.php";

//COMPETION VARIABLES
$competitionId = 1;

//LANGUAGE (0 = FINNISH, 1 = ENGLISH)
$l = 1;
$title = "Imoittautuminen Jannen Kisat 2026";
$headerImage = "background-image: url('images/header-image.jpg')";
$compNameTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:10%;";
$compDateTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:20%;";
$compLocationTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:30%;";

//SELECTING COMPETITION CLASSES
$c = new Competitions();
$competitonClasses = $c->selectCompetitonClasses($competitionId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $title; ?>
  </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
</head>

<body class="bg-dark">
  <div class="container bg-light">
    <header class="m-0 p-0">
      <div style="<?php echo $headerImage; ?>" id="header">
        <h3 style="<?php echo $compNameTextStyle; ?>">43. Jannen Kisat</h3>
        <h3 style="<?php echo $compDateTextStyle; ?>">18.7.-25.7.2026</h3>
        <h3 style="<?php echo $compLocationTextStyle; ?>">Räyskälä</h3>
      </div>
    </header>
    <div class="enrollment-form border p-1 p-md-3">
      <h4><?php echo $language[$l]["header"]; ?></h4>
      <form action="enrollment_handler.php" method="post">
        <fieldset>
          <legend><?php echo $language[$l]["fieldset-1"]; ?></legend>
          <input type="hidden" name="competition-id" value="<?php echo $competitionId; ?>">
          <div class="row">
            <div class="col-12 col-md-2">
              <label for="pilot-first-name"><?php echo $language[$l]["label-pilot-first-name"]; ?></label>
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
              <input class="form-control" type="text" name="pilot-email" id="pilot-email">
            </div>
            <div class="col-12 col-md-3">
              <label for="pilot-club"><?php echo $language[$l]["label-pilot-club"]; ?></label>
              <input class="form-control" type="text" name="pilot-club" id="pilot-club">
            </div>
          </div>
        </fieldset>
        <fieldset class="mt-3">
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
          <div class="row">
            <fieldset class="mt-3">
              <legend><?php echo $language[$l]["fieldset-3"]; ?></legend>
              <div class="col-12 col-md-3">
                <label for="competition-classes"><?php echo $language[$l]["label-select-option-competiton-class"]; ?></label>
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
            </fieldset>
          </div>
        </fieldset>
        <fieldset class="mt-3">
          <legend><?php echo $language[$l]["fieldset-4"]; ?></legend>
          <div class="row">
            <div class="col-12 col-md-5">
              <label for="flight_logger-1"><?php echo $language[$l]["label-logger-1"]; ?></label>
              <input type="file" name="flight_logger-1" id="flight_logger-1">
            </div>
            <div class="col-12 col-md-5">
              <label for="flight_logger-1"><?php echo $language[$l]["label-logger-2"]; ?></label>
              <input type="file" name="flight_logger-2" id="flight_logger-2">
            </div>
          </div>
        </fieldset>
      </form>
    </div>
    <div class="footer bg-secondary">
      <!--===== FOOTER CONTENT =====-->
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>