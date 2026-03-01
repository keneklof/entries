<?php
require "functions.php";
//SELECT COMPETITION LANGUAGE
$competitionId = 2;
$c = new Competitions();
$competitionInfo = $c->selectCompetitionInfo($competitionId);
$competitionLanguage = $competitionInfo[0]["competition_language"];
if ($competitionLanguage == "FIN") {
  $l = 0;
} else if ($competitionLanguage == "ENG") {
  $l = 1;
}
?>
 <!DOCTYPE html>
 <html lang="en">
   <head>
     <meta charset="UTF-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <title></title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
     <script src="https: //kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
   </head>
   <body class="bg-dark">
     <div class="container bg-light py-5">
      <div class="alert alert-success text-center">
       <h5><?php echo $language[$l]["delete-entry-success"]; ?></h5>
      </div>
     </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   </body>
 </html>