<?php
include "language.php";
require "functions.php";

//LOCAL TIMEZONE
date_default_timezone_set("Europe/Helsinki");

//VARIABLES
$competitionId = 2;
$cp = new Pilots();
$c = new Competitions();
$competitionInfo = $c->selectCompetitionInfo($competitionId);

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
$competitionDates = $cs->format("d.m.") . "-" . $ce->format("d.m.Y");

//LINKS
$host = $competitionInfo[0]["competition_web_host"];
$path = $competitionInfo[0]["competition_folder"];
$confirmationUrl = $host . $path . "confirmation.php";
$entriesUrl = $host . $path . "entries.php";
$emailImage = $host . $path . "images/competition-logo.png";

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
  $linkWebSite = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_web_site'] . "' target='_blank'>Websivut</a>";
  $linkSoaringSpot = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_soaringspot'] . "' target='_blank'>SoaringSpot</a>";
  $linkEntries = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $entriesUrl . "' target='_blank'>Ilmoittautuneet</a>";
} else if ($l == 1) {
  $linkWebSite = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_web_site'] . "' target='_blank'>Website</a>";
  $linkSoaringSpot = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $competitionInfo[0]['competition_soaringspot'] . "' target='_blank'>SoaringSpot</a>";
  $linkEntries = "<a class='btn btn-light btn-sm w-100 mb-2 mb-md-0' href='" . $entriesUrl . "' target='_blank'>Entries</a>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $language[$l]['entries-title']; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
  <script src="https: //kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
</head>

<body class="bg-dark">
  <div class="container bg-light pb-3 pt-2 mt-2">
    <header class="m-0 p-0">
      <div style="<?php echo $entriesHeaderImage; ?>" id="header">
        <h3 style="<?php echo $competitionNameTextStyle; ?>"><?php echo $competitionName; ?></h3>
        <h3 style="<?php echo $competitionDateTextStyle; ?>"><?php echo $competitionDates; ?></h3>
        <h3 style="<?php echo $competitionLocationTextStyle; ?>"><?php echo $competitionLocation; ?></h3>
      </div>
    </header>
    <div class="container p-3">
      <h3 class="pt-3 ml-3"><?php echo $language[$l]['entries-header']; ?></h3>
      <hr>
      <?php
      if ($l == 0) {
        foreach ($competitionClasses as $class) {
          echo "<h5>" . $class["class_name_fin"] . "</h5>";
          $counter = 0;
          echo "<div class='table-responsive mb-3 entries-table'>";
          echo "<table class='table table-sm table-striped table-bordered'>";
          echo "<thead class='fw-bold'>";
          echo "<tr>";
          echo "<td>#</td>";
          echo "<td>Pilotti</td>";
          echo "<td>Kone</td>";
          echo "<td>Tunnus</td>";
          echo "<td class='d-none d-sm-table-cell'>Kerho</td>";
          if (isset($competitionInfo[0]["competition_international"]) && $competitionInfo[0]["competition_international"] == 1) {
            echo "<td>Maa</td>";
          }
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
              echo "<td class='d-none d-sm-table-cell'>" . $pilots[$i]["pilot_club"] . "</td>";
              if (isset($competitionInfo[0]["competition_international"]) && $competitionInfo[0]["competition_international"] == 1) {
                echo "<td><img class='flag' src='images/flags/" . $pilots[$i]["pilot_country"] . ".png'></td>";
              }
              echo "</tr>";
            }
          }
          echo "</table>";
          echo "</div>";
        }
      } elseif ($l == 1) {
        foreach ($competitionClasses as $class) {
          echo "<h5>" . $class["class_name_eng"] . "</h5>";
          $counter = 0;
          echo "<div class='table-responsive mb-3 entries-table'>";
          echo "<table class='table table-sm table-striped'>";
          echo "<thead class='table-secondary fw-bold table-bordered'>";
          echo "<tr>";
          echo "<td>#</td>";
          echo "<td>Pilot</td>";
          echo "<td>Plane</td>";
          echo "<td>Sign</td>";
          echo "<td class='d-none d-sm-table-cell'>Club</td>";
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
              echo "<td class='d-none d-sm-table-cell'>" . $pilots[$i]["pilot_club"] . "</td>";
              echo "<td><img class='flag' src='images/flags/" . $pilots[$i]["pilot_country"] . ".png'></td>";
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
    <div class="entry-footer bg-secondary">

    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>