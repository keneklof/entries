<?php

class Pilots extends Database
{

  public function newPilot($competitionId, $firstName, $lastName, $phone, $email, $club, $competitionClass, $accomodation, $otherInfo, $glider, $register, $competitionSign, $wingspan, $winglets, $engine, $flarmId, $logger1, $logger2, $entryTime)
  {

    //INSERTING INTO DATABASE
    try {
      $sql = "INSERT INTO pilots VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([null, $competitionId, $firstName, $lastName, $phone, $email, $club, $competitionClass, $accomodation, $otherInfo, 0, $glider, $register, $competitionSign, $wingspan, $winglets, $engine, $flarmId, $logger1, $logger2, $entryTime]);
      return 1;
    } catch (PDOException $e) {
      file_put_contents('error_new_entry.txt', date('d.m.Y G:i') . 'Pilotin lisääminen:' . $e->getMessage() . "\n", FILE_APPEND);
      return 0;
    }
    /*$entryInfo = array();
      array_push($entryInfo, $competitionId, $firstName, $lastName, $phone, $email, $club, $accomodation, $otherInfo, $entryfee, $glider, $register, $competitonSign, $wingspan, $winglets, $engine, $flarmId,$competitionClass, $logger1, $logger2, $entryTime);
      return $entryInfo;*/
  }
}
