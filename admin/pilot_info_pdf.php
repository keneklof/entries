<?php
$competitionId = 2;
$competitionName = "Purjelennon SM 2026";
require "../functions.php";
require "fpdf/fpdf.php";

class PDF extends FPDF
{
  public function Header()
  {
    global $competitionName;
    $this->SetLeftMargin(15);
    $this->SetRightMargin(15);
    $this->setFont('Arial', '', '14');
    $date = Date('j.n.');
    $str  = 'Pilotti-info ' . $competitionName;
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
    $year = Date('Y');
    $this->Line(10, 282, 200, 282);
    // Go to 1.5 cm from bottom
    $this->SetY(-15);
    // Select Arial italic 8
    $this->SetFont('Arial', 'I', 8);
    $str2 = mb_convert_encoding("GeeCM  ©Ken Eklöf ", "ISO-8859-1", "UTF-8");
    // Print centered page number
    $this->Cell(65, 10, $str2 . $year, 0, 0, 'L');
    $this->Cell(60, 10, $now . '  ' . $competitionName, 0, 0, 'C');
    $this->Cell(55, 10, 'Sivu ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }
}

$pdf   = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->SetLeftMargin(15);
$pdf->SetRightMargin(15);
$pdf->AddPage();
$pdf->setFont('Arial', 'B', '12');
$search = array("ö", "å", "ä", "Ö", "Å", "Ä", " ");
$replace = array("o", "a", "a", "O", "A", "A", "_");

$printCompetitionName = str_replace($search, $replace, $competitionName);

$pdf->Output('I', "pilotti_info_" . strtolower($printCompetitionName) . ".pdf");
