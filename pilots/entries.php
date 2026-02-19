<?php
include "../autoloader.php";
include "../functions.php";
include "../language.php";

$competitionId = 1;
$c = new Competitions();
//SELECT COMPETITON LANGUAGE
$l = $c->selectCompetitionLanguage($competitionId);

//SELECT ALL COMPETITION INFO
$competitionInfo = $c->selectCompetitionInfo($competitionId);

$cp = new Pilots();
//SELECT COMPETITON PILOTS
$pilots = $cp->selectCompetitionPilots($competitionId);

//SELECT COMPETITON CLASSES
$classes = $c->selectCompetitionClasses($competitionId);

//CREATING COMPETITION DATES
$cs = new DateTime($competitionInfo[0]["competition_start"]);
$ce = new DateTime($competitionInfo[0]["competition_end"]);
$competitionDates = $cs->format("d.m.") . "-" . $ce->format("d.m.Y");

//HEADER INFO
$competitionTitle = $competitionInfo[0]["competition_name"];
$competitionLocation = $competitionInfo[0]["competition_location"];

//HEADER STYLING
//Background image size 1000x300
$headerImage = "background-image: url('../images/header-image.jpg')";
$competitionNameTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:10%;";
$competitionDateTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:20%;";
$competitionLocationTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:30%;";


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $language[$l]['entries-title']; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="entries_style.css">
  <script src="https: //kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
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
    <div class="container p-3">
      <h3 class="py-3 ml-3"><?php echo $language[$l]['entries-header']; ?></h3>
      <?php
      if ($l == 0) {
        foreach ($classes as $class) {
          echo "<h5>" . $class["class_name_fin"] . "</h5>";
          $counter = 0;
          echo "<div class='table-responsive mb-3'>";
          echo "<table class='table table-striped table-bordered'>";
          echo "<thead class='fw-bold'>";
          echo "<tr>";
          echo "<td>#</td>";
          echo "<td>Pilotti</td>";
          echo "<td>Kone</td>";
          echo "<td>Tunnus</td>";
          echo "<td>Kerho</td>";
          echo "<td>Maa</td>";
          echo "</thead>";
          for ($i = 0; $i < count($pilots); $i++) {
            if ($pilots[$i]["plane_class"] == $class["class_id"]) {
              $counter++;
              echo "</tr>";
              echo "<tr;>";
              echo "<td>" . $counter . "</td>";
              echo "<td>" . $pilots[$i]["pilot_last_name"] . " " . $pilots[$i]["pilot_first_name"] . "</td>";
              echo "<td>" . $pilots[$i]["plane_type"] . "</td>";
              echo "<td>" . $pilots[$i]["plane_competition_sign"] . "</td>";
              echo "<td>" . $pilots[$i]["pilot_club"] . "</td>";
              echo "<td><img class='flag' src='../images/flags/1.png'></td>";
              echo "</tr>";
            }
          }
          echo "</table>";
          echo "</div>";
        }
      } elseif ($l == 1) {
        foreach ($classes as $class) {
          echo "<h5>" . $class["class_name_eng"] . "</h5>";
          $counter = 0;
          echo "<div class='table-responsive mb-3'>";
          echo "<table class='table table-striped'>";
          echo "<thead class='table-secondary fw-bold table-bordered'>";
          echo "<tr>";
          echo "<td>#</td>";
          echo "<td>Pilot</td>";
          echo "<td>Plane</td>";
          echo "<td>Sign</td>";
          echo "<td>Club</td>";
          echo "<td>Country</td>";
          echo "</thead>";
          for ($i = 0; $i < count($pilots); $i++) {
            if ($pilots[$i]["plane_class"] == $class["class_id"]) {
              $counter++;
              echo "</tr>";
              echo "<tr;>";
              echo "<td>" . $counter . "</td>";
              echo "<td>" . $pilots[$i]["pilot_last_name"] . " " . $pilots[$i]["pilot_first_name"] . "</td>";
              echo "<td>" . $pilots[$i]["plane_type"] . "</td>";
              echo "<td>" . $pilots[$i]["plane_competition_sign"] . "</td>";
              echo "<td>" . $pilots[$i]["pilot_club"] . "</td>";
              echo "<td><img class='flag' src='../images/flags/1.png'></td>";
              echo "</tr>";
            }
          }
          echo "</table>";
          echo "</div>";
        }
      }

      ?>
      </table>
    </div>
  </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>