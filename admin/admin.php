<?php
include "../functions.php";
include "../variables.php";

$c= new Competitions();
$info = $c->selectCompetitionInfo($competitionId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="admin.css">
  <script src="https: //kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
</head>

<body>
  <div class="container p-3">
    <h3 class="text-center">Hallintapaneeli</h3>
    <button class="pilot-info">Section 1</button>
    <div class="panel">
      <p><?php echo $info[0]["competition_name"]; ?>
      </p>
    </div>

    <button class="pilot-info">Section 2</button>
    <div class="panel">
      <p>Ken Eklöf</p>
    </div>

    <button class="pilot-info">Section 3</button>
    <div class="panel">
      <p>Lorem ipsum...</p>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="admin.js"></script>
</body>

</html>