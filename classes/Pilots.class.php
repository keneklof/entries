<?php

class Pilots extends Database
{

  public function newPilot($competitionId, $firstName, $lastName, $phone, $email, $club, $pilotCountry, $competitionClass, $accomodation, $otherInfo, $glider, $register, $competitionSign, $wingspan, $winglets, $engine, $flarmId, $logger1, $logger2, $pilotLinkId, $entryTime)
  {

    //INSERTING INTO DATABASE
    try {
      $sql = "INSERT INTO pilots VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([null, $competitionId, $firstName, $lastName, $phone, $email, $club, $pilotCountry, $competitionClass, $accomodation, $otherInfo, 0, $glider, $register, $competitionSign, $wingspan, $winglets, $engine, $flarmId, $logger1, $logger2, $pilotLinkId, $entryTime, $entryTime]);
      return 1;
    } catch (PDOException $e) {
      file_put_contents('error_new_entry.txt', date('d.m.Y G:i') . 'Pilotin lisääminen:' . $e->getMessage() . "\n", FILE_APPEND);
      return 0;
    }
    /*$entryInfo = array();
      array_push($entryInfo, $competitionId, $firstName, $lastName, $phone, $email, $club, $accomodation, $otherInfo, $entryfee, $glider, $register, $competitonSign, $wingspan, $winglets, $engine, $flarmId,$competitionClass, $logger1, $logger2, $entryTime);
      return $entryInfo;*/
  }

  public function updatePilot($competitionId, $pilotLinkId, $firstName, $lastName, $phone, $email, $club, $pilotCountry, $competitionClass, $accomodation, $otherInfo, $glider, $register, $competitionSign, $wingspan, $winglets, $engine, $flarmId, $logger1, $logger2, $updateTime)
  {
    //UPDATING DATABASE
    try {
      $sql = "UPDATE pilots SET pilot_first_name = ?, pilot_last_name = ?, pilot_phone = ?, pilot_email = ?, pilot_club = ?, pilot_country = ?, plane_class = ?, pilot_accomodation = ?, pilot_other_info = ?, plane_type = ?, plane_register = ?, plane_competition_sign = ?, plane_wingspan = ?, plane_winglets = ?, plane_engine = ?, plane_flarm_id = ?, plane_logger_one = ?, plane_logger_two = ?, update_time = ? WHERE competition_id = ? AND pilot_link_id = ?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$firstName, $lastName, $phone, $email, $club, $pilotCountry, $competitionClass, $accomodation, $otherInfo, $glider, $register, $competitionSign, $wingspan, $winglets, $engine, $flarmId, $logger1, $logger2, $updateTime, $competitionId, $pilotLinkId]);
      return 1;
    } catch (PDOException $e) {
      file_put_contents('error_update_pilot.txt', date('d.m.Y G:i') . 'Pilotin tietojen päivitys:' . $e->getMessage() . "\n", FILE_APPEND);
      return 0;
    }
  }

  public function selectCompetitionPilots($competitionId)
  {

    //FETCH COMPETITION PILOTS
    try {
      $sql    = ("SELECT * FROM view_competition_pilots WHERE competition_id = '$competitionId' ORDER BY pilot_last_name");
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

  private function entryFeeCheck($pilotLinkId)
  {
    //FETCH COMPETITION PILOTS
    try {
      $sql    = ("SELECT pilot_entry_fee FROM pilots WHERE pilot_link_id = '$pilotLinkId'");
      $stmt   = $this->connect()->query($sql);
      $entryFee = $stmt->fetchColumn();
      return $entryFee;
    } catch (PDOException $e) {
      file_put_contents('error_checking_pilot_entry_fee.txt', date('d.m.Y G:i') . 'Fetching pilot_entry_fee:' . $e->getMessage() . "\n", FILE_APPEND);
    }
  }

  public function upDateEntryFee($pilotLinkId, $paid)
  {

    try {
      $sql = "UPDATE pilots SET pilot_entry_fee = ? WHERE pilot_link_id = ?";
      $stmt = $this->connect()->prepare($sql);
      if ($paid == 1) {
        $stmt->execute([0, $pilotLinkId]);
        return 0;
      } else if ($paid == 0) {
        $stmt->execute([1, $pilotLinkId]);
        return 1;
      }
    } catch (PDOException $e) {
      file_put_contents('error_update_entry_fee.txt', date('d.m.Y G:i') . 'Pilotin maksun päivitys:' . $e->getMessage() . "\n", FILE_APPEND);
      return 0;
    }
  }

  public function deletePilot($pilotLinkId)
  {

    //DELETE COMPETITION PILOT
    try {
      $sql    = ("DELETE FROM pilots WHERE pilot_link_id = ?");
      $stmt   = $this->connect()->prepare($sql);
      $stmt->execute([$pilotLinkId]);
      return 1;
    } catch (PDOException $e) {
      file_put_contents('error_deleting_pilot_pilot.txt', date('d.m.Y G:i') . ' Deleting pilot:' . $e->getMessage() . "\n", FILE_APPEND);
      return 0;
    }
  }
}
