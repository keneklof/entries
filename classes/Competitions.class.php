<?php

class Competitions extends Database
{
  public $compId;

  public function selectCompetitionInfo($compId)
  {
    $this->compId = $compId;

    //FETCH COMPETITION INFO
    try {
      $sql    = ("SELECT * FROM competitions WHERE competition_id = '$compId'");
      $stmt   = $this->connect()->query($sql);
      $competitions = $stmt->fetchAll();
    } catch (PDOException $e) {
      file_put_contents('error_fetching_competition.txt', date('d.m.Y G:i') . 'Fetching competition:' . $e->getMessage() . "\n", FILE_APPEND);
    }
    return $competitions;
  }

  public function selectCompetitonClasses($compId)
  {

    try {
      $sql    = ("SELECT class_id, comp_id, class_name_fin, class_name_eng FROM view_competition_classes WHERE comp_id = $compId");
      $stmt   = $this->connect()->query($sql);
      $classes = $stmt->fetchAll();
    } catch (PDOException $e) {
      file_put_contents('error_fetching_competiton_classes.txt', date('d.m.Y G:i') . 'Fetching competition classes:' . $e->getMessage() . "\n", FILE_APPEND);
    }
    return $classes;
  }
}
