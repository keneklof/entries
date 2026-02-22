<?php

class Pilots extends Database
{

  public function newPilot($competitionId, $firstName, $lastName, $phone, $email, $club, $country, $competitionClass, $accomodation, $otherInfo, $glider, $register, $competitionSign, $wingspan, $winglets, $engine, $flarmId, $logger1, $logger2, $pilotLinkId, $entryTime)
  {

    //INSERTING INTO DATABASE
    try {
      $sql = "INSERT INTO pilots VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([null, $competitionId, $firstName, $lastName, $phone, $email, $club, $country, $competitionClass, $accomodation, $otherInfo, 0, $glider, $register, $competitionSign, $wingspan, $winglets, $engine, $flarmId, $logger1, $logger2, $pilotLinkId, $entryTime]);
      return 1;
    } catch (PDOException $e) {
      file_put_contents('error_new_entry.txt', date('d.m.Y G:i') . 'Pilotin lisääminen:' . $e->getMessage() . "\n", FILE_APPEND);
      return 0;
    }
    /*$entryInfo = array();
      array_push($entryInfo, $competitionId, $firstName, $lastName, $phone, $email, $club, $accomodation, $otherInfo, $entryfee, $glider, $register, $competitonSign, $wingspan, $winglets, $engine, $flarmId,$competitionClass, $logger1, $logger2, $entryTime);
      return $entryInfo;*/
  }

  public function selectCompetitionPilots($competitionId)
  {

    //FETCH COMPETITION PILOTS
    try {
      $sql    = ("SELECT * FROM view_competition_pilots WHERE competition_id = '$competitionId'");
      $stmt   = $this->connect()->query($sql);
      $pilots = $stmt->fetchAll();
    } catch (PDOException $e) {
      file_put_contents('error_fetching_competition_pilots.txt', date('d.m.Y G:i') . 'Fetching competition:' . $e->getMessage() . "\n", FILE_APPEND);
    }
    return $pilots;
  }

  public function selectSinglePilotInfo($competitionId, $pilotLinkId)
  {

    //FETCH COMPETITION PILOTS
    try {
      $sql    = ("SELECT * FROM view_competition_pilots WHERE competition_id = '$competitionId' AND pilot_link_id = '$pilotLinkId'");
      $stmt   = $this->connect()->query($sql);
      $pilotInfo = $stmt->fetchAll();
    } catch (PDOException $e) {
      file_put_contents('error_fetching_single_pilot.txt', date('d.m.Y G:i') . 'Fetching single pilot info:' . $e->getMessage() . "\n", FILE_APPEND);
    }
    return $pilotInfo;
  }
}
