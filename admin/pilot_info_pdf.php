<?php
require "../functions.php";
require "..//competition_id.php";
require "fpdf/fpdf.php";

$cp = new Pilots();
$c = new Competitions();

$competitionInfo = $c->selectCompetitionInfo($competitionId);

//CREATING COMPETITION DATES
$cs = new DateTime($competitionInfo[0]["competition_start"]);
$ce = new DateTime($competitionInfo[0]["competition_end"]);
$competitionDates = $cs->format("j.n.") . "-" . $ce->format("j.n.");


//HEADER INFO
$competitionName = $competitionInfo[0]["competition_name"];
$competitionLocation = $competitionInfo[0]["competition_location"];

//CLASSES
$classes = $c->selectCompetitionClasses($competitionId);

//PILOTS
$pilots = $cp->selectCompetitionPilots($competitionId);

class PDF extends FPDF
{
  public function Header()
  {
    global $competitionName;
    global $competitionDates;
    global $competitionLocation;
    $this->SetLeftMargin(15);
    $this->SetRightMargin(15);
    $this->setFont('Arial', '', '14');
    $date = Date('j.n.');
    $str  = mb_convert_encoding($competitionName . "     " . $competitionDates . " " . $competitionLocation . "     Pilotit", "ISO-8859-1", "UTF-8");
    $this->setLineWidth(0.1);
    $this->Line(15, 19, 195, 19);
    $this->Cell(0, 10, $str, 0, 1, 'L');
    $this->Cell(0, 7, '', 0, 1, 'C');
    $this->setFont('Arial', '', '12');
    $this->setLineWidth(0.1);
  }
  public function Footer()
  {
    global $competitionName;
    $now = Date('j.n.Y  H:i:s');
    $this->Line(10, 282, 200, 282);
    // Go to 1.5 cm from bottom
    $this->SetY(-15);
    // Select Arial italic 8
    $this->SetFont('Arial', 'I', 8);
    $created = mce("Created by Ken Eklöf 2026 ");
    // Print centered page number
    $this->Cell(65, 10, $created, 0, 0, 'L');
    $this->Cell(60, 10, $now . '  ' . $competitionName, 0, 0, 'C');
    $this->Cell(55, 10, 'Sivu ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }
}

$pdf   = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->SetLeftMargin(15);
$pdf->SetRightMargin(15);
$pdf->AddPage();

foreach ($classes as $class) {
  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(30, 9, $class["class_name_fin"], 0, 1, 'L', 1);
  $counter = 1;
  foreach ($pilots as $pilot) {
    $pdf->SetFont('Arial', '', 11);
    if ($pilot["plane_class"] == $class["class_id"] && $pilot["competition_id"] == $class["competition_id"]) {
      $pdf->Cell(10, 5, $counter.".", 0, 0, 'L', 1);
      $pdf->Cell(40, 5, mce($pilot["pilot_last_name"] . " " . $pilot["pilot_first_name"]), 0, 0, 'L', 1);
      $pdf->Cell(20, 5, mce($pilot["plane_competition_sign"]), 0, 0, 'L', 1);
      $pdf->Cell(40, 5, mce($pilot["plane_type"]), 0, 0, 'L', 1);
      $pdf->Cell(70, 5, mce($pilot["pilot_club"]), 0, 1, 'L', 1);
      $counter++;
    }
  }
}

$pdf->setFont('Arial', 'B', '12');
$search = array("ö", "å", "ä", "Ö", "Å", "Ä", " ");
$replace = array("o", "a", "a", "O", "A", "A", "_");

$printCompetitionName = str_replace($search, $replace, $competitionName);

$pdf->Output('I', "pilotti_info_" . strtolower($printCompetitionName) . ".pdf");
