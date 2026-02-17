<?php
include "../autoloader.php";
include "../functions.php";
include "../language.php";

$competitionId = 1;
$c = new Competitions();
$l = $c->selectCompetitionLanguage($competitionId);

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
      <div style="background-image: url('../images/header-image.jpg')" id="header">
      </div>
    </header>
    <h3 class="text-start"><?php echo $language[$l]['entries-header']; ?></h3>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>