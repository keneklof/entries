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
  <title>Admin&nbsp;<?php echo $competitionInfo[0]["competition_name"]; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="admin.css">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script src="https: //kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
  <script src="admin.jss"></script>
</head>

<body class="bg-secondary">
  <div class="container p-3 p-md-5 bg-light">
    <h4 class="text-center">Admin&nbsp;<?php echo $competitionInfo[0]["competition_name"]; ?></h4>
    <?php
    foreach ($competitionClasses as $class) {
      echo "<h5>" . $class['class_name_fin'] . "</h5>";
      echo "<div class='table-responsive'>";
      echo "<table class='table table-sm mb-4 table-pilots'>";
      echo "<thead><tr class='table-secondary'>
      <td>#</td>
      <td>Pilotti</td>
      <td class='d-none d-sm-table-cell'>Kone</td>
      <td class='d-none d-sm-table-cell'>KT</td>
      <td>FlarmID</td>
      <td>Lgr1</td>
      <td>Lgr2</td>
      <td>Ilm.maksu</td>
      </tr></thead";
      $counter = 0;
      foreach ($pilots as $pilot) {
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
          <tr></tr>
          <td>" . $counter . ".</td>
          <td><a href='../pilot_update.php?pilot_id=" . $pilot['pilot_link_id'] . "'" . " target='_blank'>" . $pilot['pilot_last_name'] . "&nbsp;" . $pilot['pilot_first_name'] . "</a></td>
          <td class='d-none d-sm-table-cell'>" . $pilot['plane_type'] . "</td>
          <td class='d-none d-sm-table-cell'>" . $pilot['plane_competition_sign'] . "</td>
          <td>" . $flarmId . "</td>
          <td>" . $logger1 . "</td>
          <td>" . $logger2 . "</td>";
          echo "<td>"; ?>
          <?php
          if (isset($pilot["pilot_entry_fee"]) && $pilot["pilot_entry_fee"] == 0) { ?>
            <button class="btn btn-sm btn-danger btn-entry-fee animate__animated" id="<?php echo $pilot['pilot_link_id']; ?>" onclick="changeEntryFee(this.id)">Ei</button>
          <?php } else if ((isset($pilot["pilot_entry_fee"]) && $pilot["pilot_entry_fee"] == 1)) { ?>
            <button class="btn btn-sm btn-success btn-entry-fee animate__animated" id="<?php echo $pilot['pilot_link_id']; ?>" onclick="changeEntryFee(this.id)">Kyllä</button>
    <?php }
          echo "</td>";
          echo "</tr>";
        }
      }
      echo "</table>";
      echo "</div>";
      $counter == 0;
    }
    ?>
    <div class="row">
      <div class="col-12 col-md-2 offset-md-5">
        <a href="pilot_info_pdf.php" class="btn btn-sm btn-primary w-100" role="button" target="_blank">Pilotit PDF</a>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="admin.js"></script>
</body>

</html>