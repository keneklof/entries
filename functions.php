<?php
include "autoloader.php";
include "language.php";
//MAIL PREFERENCES
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//COMPETITION ID
//$competitionId = 2;
//$host = $competitionInfo[0]["competition_web_host"];

//CHECKING THE INPUTS
function checkInput($data): string
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function sendConfirmationMail($competitionId, $competitionName, $pilotLinkId, $pilotLinkUpdate, $entriesUrl, $emailImage, $firstName, $lastName, $email)
{
  include "language.php";
  //SELECT ALL COMPETITION INFO
  $c = new Competitions();
  $competitionInfo = $c->selectCompetitionInfo($competitionId);

  //FETCHING SINGLE PILOT INFORMATION FOR UPDATE
  $pi = new Pilots();
  $pilotInfo = $pi->selectSinglePilotInfo($competitionId, $pilotLinkId);

  //SEND EMAIL TO PILOT
  //Create an instance; passing `true` enables exceptions
  //SELECT COMPETITION LANGUAGE
  $competitionLanguage = $competitionInfo[0]["competition_language"];
  if ($competitionLanguage == "FIN") {
    $l = 0;
    $mailTitle = "Ilmoittautuminen";
  } else if ($competitionLanguage == "ENG") {
    $l = 1;
    $mailTitle = "Competition entry";
  }
  if ($l == 0) {
    $mailBody =
      "<div style='margin-bottom = 5px;font-size: 1.1em;'><img src=" . $emailImage . " alt='Logo' style='width:250px'></div>
  <h3> Hei&nbsp;" . $pilotInfo[0]['pilot_first_name'] . "!</h3>
  <p>" . mb_strtoupper($competitionName, 'UTF-8') . "</p>
  <p>ILMOITTAUTUMISTIEDOT</p>
  <table>
  <tr><td>Nimi: </td><td>" . $lastName . "&nbsp;" . $firstName . "</td></tr>
  <tr><td>Kone: </td><td>" . $pilotInfo[0]['plane_type'] . "</td></tr>
  <tr><td>Rekisteri: </td><td>" . $pilotInfo[0]['plane_register'] . "</td></tr>
  <tr><td>Kilpailutunnus: </td><td>" . $pilotInfo[0]['plane_competition_sign'] . "</td></tr>
  <tr><td>Kilpailuluokka: </td><td>" . $pilotInfo[0]['class_name_fin'] . "</td></tr>
  <tr><td>Logger 1: </td><td>" . $pilotInfo[0]['plane_logger_one'] . "</td></tr>
  <tr><td>Logger 2: </td><td>" . $pilotInfo[0]['plane_logger_two'] . "</td></tr>
  <tr><td>FlarmID: </td><td>" . $pilotInfo[0]['plane_flarm_id'] . "</td></tr>
  </table>
  <p>Voit tarkistaa ja päivittää tietosi:&nbsp;<a href='" . $pilotLinkUpdate . "' target='_blank'>täältä</a></p>
  <p><a href='" . $entriesUrl . "' target='_blank'>" . $language[$l]["mail-entries-url"] . "</a></p>
  <p>Tervetuloa kisoihin!</p>";
  } else if ($l == 1) {
    $mailBody =
      "<div style='margin-bottom = 5px;font-size: 1.1em;'><img src=" . $emailImage . " alt='Logo' style='width:250px'></div>
  <h3> Hi&nbsp;" . $pilotInfo[0]['pilot_first_name'] . "!</h3>
  <p>" . mb_strtoupper($competitionName, 'UTF-8') . "</p>
  <p>ENTRY INFORMATION</p>
  <table>
  <tr><td>Name: </td><td>" . $lastName . "&nbsp;" . $firstName . "</td></tr>
  <tr><td>Glider: </td><td>" . $pilotInfo[0]['plane_type'] . "</td></tr>
  <tr><td>Register: </td><td>" . $pilotInfo[0]['plane_register'] . "</td></tr>
  <tr><td>Competition sign: </td><td>" . $pilotInfo[0]['plane_competition_sign'] . "</td></tr>
  <tr><td>Class: </td><td>" . $pilotInfo[0]['class_name_eng'] . "</td></tr>
  <tr><td>Logger 1: </td><td>" . $pilotInfo[0]['plane_logger_one'] . "</td></tr>
  <tr><td>Logger 2: </td><td>" . $pilotInfo[0]['plane_logger_two'] . "</td></tr>
  <tr><td>FlarmID: </td><td>" . $pilotInfo[0]['plane_flarm_id'] . "</td></tr>
  </table>
  <p>You can check and update your information&nbsp;<a href='" . $pilotLinkUpdate . "' target='_blank'>here</a></p>
  <p><a href='" . $entriesUrl . "' target='_blank'>" . $language[$l]["mail-entries-url"] . "</a></p>
  <p>Welcome to the competition!</p>";
  }

  $mail = new PHPMailer(true);

  try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'mail.digiken.fi';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true; //Enable SMTP authentication 
    $mail->CharSet    = 'UTF-8';
    $mail->Username   = 'ken.eklof@digiken.fi';                     //SMTP username
    $mail->Password   = 'x.6tF&fYK!H+';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->setFrom('ken.eklof@digiken.fi', $mailTitle);
    $mail->addAddress($email);     //Add a recipient
    $mail->addReplyTo('ken.eklof@digiken.fi');
    $mail->addBCC("keneklof@outlook.com");


    //Attachments
    //$mail->addAttachment();         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $competitionName;
    $mail->Body    = $mailBody;
    $mail->AltBody = 'Sähköpostipostiohjemasi ei tue HTML muotoilua';

    $mail->send();
  } catch (Exception $e) {
    file_put_contents('error_confirmation_mail.txt', date('d.m.Y G:i') . 'Ilmoittautumisvahvistus:' . $e->getMessage() . "\n", FILE_APPEND);
  }
}
