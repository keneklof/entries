<?php

//MAIL PREFERENCES
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//COMPETITION ID
$competitionId = 1;
$host = $competitionInfo[0]["competition_web_host"];

//SELECT ALL COMPETITION INFO
$c = new Competitions();
$competitionInfo = $c->selectCompetitionInfo($competitionId);

//CHECKING THE INPUTS
function checkInput($data): string
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function sendConfirmationMail($competitionId, $pilotLinkId, $emailImage)
{

  $pi = new Pilots();

  $pilotInfo = $pi->selectSinglePilotInfo($competitionId, $pilotLinkId);

  //SEND EMAIL TO PILOT
  //Create an instance; passing `true` enables exceptions
  $mailBodyFIN =
  "<div><img src=" . $emailImage . " alt='Logo' style='width:250px'></div>
  <h3> Hei!" . $pilotInfo[0]['pilot_first_name'] . "</h3>
  <p>ILMOITTAUTUMISTIEDOT</p>
  <p>" . $pilotInfo[0]['plane_logger_one']. "</p>
  <p>" . $pilotInfo[0]['plane_logger_two'] . "</p>";
  
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
    //Recipients
    $mail->setFrom('ken.eklof@digiken.fi', 'Ilmoittautuminen');
    $mail->addAddress('keni.eklof@gmail.com', 'Ilmoittautuminen');     //Add a recipient
    $mail->addReplyTo('ken.eklof@digiken.fi', 'Ilmoittautuminen');
    $mail->addBCC("keneklof@outlook.com");

    //Attachments
    //$mail->addAttachment();         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Ilmoittautuminen';
    $mail->Body    = $mailBodyFIN;
    $mail->AltBody = 'Sähköpostipostiohjemasi ei tue HTML muotoilua';

    $mail->send();
  } catch (Exception $e) {
    file_put_contents('error_confirmation_mail_FIN.txt', date('d.m.Y G:i') . 'Ilmoittautumisvahvistus:' . $e->getMessage() . "\n", FILE_APPEND);
  }
}
