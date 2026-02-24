<?php
include "autoloader.php";

 //LOCAL TIMEZONE
date_default_timezone_set("Europe/Helsinki");

//ACTUAL TIME
$actualTime = new DateTime ("now");

//COMPETITION ID
$competitionId = 1;
$c = new Competitions();

//SELECT ALL COMPETITION INFO
$competitionInfo = $c->selectCompetitionInfo($competitionId);
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
$entriesUrl = $host . $path. "pilots/entries.php";
$emailImage = $host . $path . "images/competition-logo.png";

//HEADER INFO
$competitionName = $competitionInfo[0]["competition_name"];
$competitionLocation = $competitionInfo[0]["competition_location"];

//HEADER STYLING
//Background image size 1000x300
$headerImage = "background-image: url('images/header-image.jpg')";
$confirmationHeaderImage = "images/competition-logo.png";//ATTENTION! .PNG FILE EXTENSION
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
 
?>
