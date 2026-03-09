<?php
include "../functions.php";
require "../competition_id.php";

$c = new Competitions();
$competitionClasses = $c->selectCompetitionClasses($competitionId);
$competitionInfo = $c->selectCompetitionInfo($competitionId);
$cp = new Pilots();
$pilots = $cp->selectCompetitionPilots($competitionId);

//LINKS
$linkSoaringSpot = "<a class='btn btn-outline-secondary btn-sm w-100' href='" . $competitionInfo[0]['competition_soaringspot'] . "' target='_blank'>SoaringSpot</a>";
$host = $competitionInfo[0]["competition_web_host"];
$path = $competitionInfo[0]["competition_folder"];
$entriesUrl = $host . $path . "entries.php";

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
    <ul>
      <li>FLARM JA LOGGERIT</li>
      <ul>
        <li>Tämä sivu toimii realiajassa.</li>
        <li>Kun pilotti syöttä FlarmId:nsä tai lataa IGC-tiedostonsa, se näkyy heti.</li>
      </ul>
      <li>PILOTIN NIMI</li>
      <ul>
        <li>On linkki pilotin ilmoittautumiskaavakkeeseen.</li>
        <li>Pilotti käyttää samaa kaavaketta päivittääkseen tietojaan.</li>
      </ul>
      <li>ILMOITTAUTUMISMAKSU</li>
      <ul>
        <li>Klikkaamalla ilmoittautumismaksun painiketta voi merkitä maksun maksetuksi/ei maksetuksi (oletuksena ei maksettu)</li>
        <li>Klikatessa painiketta se välähtää pari kertaa, muuttaa väriä ja uusi tieto on tallennettu tietokantaan.</li>
        <li>Maksetun ja ei maksetun välillä voi vaihtaa kuinka monta kertaa tahansa</li>
        <li>Kun pilotti avaa oman lomakkeensa, hän näkee onko kilpailunjärjestäjä huomioinut ilmoittautumismaksun maksamisen.</li>
      </ul>
      <li>MUUTA</li>
      <ul>
        <li>Laitan tämän sivun linkin GeeCM etusivulle</li>
        <li>Sivu kannatta päivittää muutosten jälkeen varmistaakseen, että muutokset ovat tallentuneet oikein.</li>
      </ul>
    </ul>
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
            $flarmId = "<span class='text-danger'>&#128308</span>";
          } else {
            $flarmId = "<span class='text-success'>&#128994;</span>";
          }
          if ($pilot["plane_logger_one"] == "---") {
            $logger1 = "<span class='text-danger'>&#128308</span>";
          } else {
            $logger1 = "<span class='text-success'>&#128994;</span>";
          }
          if ($pilot["plane_logger_two"] == "---") {
            $logger2 = "<span class='text-danger'>&#128308</span>";
          } else {
            $logger2 = "<span class='text-success'>&#128994;</span>";
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
    <div class="row my-3">
      <div class="col-12 col-md-2 offset-md-3 mb-3 mb-md-3">
        <a href="pilot_info_pdf.php" class="btn btn-sm btn-outline-secondary w-100" role="button" target="_blank">Pilotit PDF</a>
      </div>
      <div class="col-12 col-md-2 mb-3 mb-md-3">
        <a href="<?php echo $entriesUrl; ?>" class="btn btn-sm btn-outline-secondary w-100" role="button" target="_blank">Ilmoittautuneet</a>
      </div>
      <div class="col-12 col-md-2 mb-3 mb-md-3">
        <?php echo $linkSoaringSpot; ?>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="admin.js"></script>
</body>

</html>