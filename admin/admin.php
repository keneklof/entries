<?php
include "../functions.php";
$competitionId = 2;
$c = new Competitions();
$competitionClasses = $c->selectCompetitionClasses($competitionId);
$competitionInfo = $c->selectCompetitionInfo($competitionId);
$cp = new Pilots();
$pilots = $cp->selectCompetitionPilots($competitionId);
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
    <h4 class="text-center">Hallintapaneeli&nbsp;<?php echo $competitionInfo[0]["competition_name"]; ?></h4>
    <?php
     foreach ($competitionClasses as $class) {
      echo "<h5>".$class['class_name_fin']."</h5>";
     } 
    ?>
    
    <div class="accordion" id="accordionExample">
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button bg-info text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            Sucksdorff Martti
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="admin.js"></script>
</body>

</html>