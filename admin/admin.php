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

<body class="bg-secondary">
  <div class="container p-3 bg-light">
    <h4 class="text-center">Hallintapaneeli&nbsp;<?php echo $competitionInfo[0]["competition_name"]; ?></h4>
    <?php
    foreach ($competitionClasses as $class) {
      echo "<h5>" . $class['class_name_fin'] . "</h5>";
      echo "<table class='table table-sm mb-4'>";
      echo "<thead><tr class='table-secondary'>
      <td>#</td>
      <td>Pilotti</td>
      <td>Kone</td>
      <td>KT</td>
      <td>Flarm</td>
      <td>Logger 1</td>
      <td>Logger 2</td>
      <td>Ilm.maksu</td>
      </tr></thead";
      foreach ($pilots as $pilot) {
        $counter = 0;
        if ($pilot["plane_class"] == $class["class_id"] && $pilot["competition_id"] == $competitionId) {
          $counter++;
          if ($pilot["plane_flarm_id"] == "---") {
            $flarmId = "<span class='text-danger'>&#10060</span>";
          } else {
            $flarmId = "<span class='text-success'>&#9989;</span>";
          }
          if ($pilot["plane_logger_one"] == "---") {
            $logger1 = "<span class='text-danger'>&#10060</span>";
          } else {
            $logger1 = "<span class='text-success'>&#9989;</span>";
          }
          if ($pilot["plane_logger_two"] == "---") {
            $logger2 = "<span class='text-danger'>&#10060</span>";
          } else {
            $logger2 = "<span class='text-success'>&#9989;</span>";
          }
          if ($pilot["pilot_entry_fee"] == 0) {
            $entryFee = "<span class='text-danger'>&#10060</span>";
          } else {
            $entryFee = "<span class='text-success'>&#9989;</span>";
          }
          echo "<tr>
          <td>" . $counter . "</td>
          <td>" . $pilot['pilot_last_name'] . "&nbsp;" . $pilot['pilot_first_name'] . "</td>
          <td>" . $pilot['plane_type'] . "</td>
          <td>" . $pilot['plane_competition_sign'] . "</td>
          <td>" . $flarmId . "</td>
          <td>" . $logger1 . "</td>
          <td>" . $logger2 . "</td>
          <td>" . $entryFee . "</td>";
        }
        echo "</table>";
        $counter == 0;
      }
    }
    ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="admin.js"></script>
</body>

</html>