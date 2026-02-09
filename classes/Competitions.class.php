<?php

class Competitions extends Database
{
  public $compId;

  public function selectCompetition($compId)
  {
    $this->compId = $compId;

    //FETCH ALL DONATORS
    try {
      $sql    = ("SELECT * FROM competitions WHERE competition_id = '$compId'");
      $stmt   = $this->connect()->query($sql);
      $competitions = $stmt->fetchAll();
    } catch (PDOException $e) {
      file_put_contents('error_fetching_competition.txt', date('d.m.Y G:i') . 'Fetching competition:' . $e->getMessage() . "\n", FILE_APPEND);
    }
    return $competitions;
  }
}
