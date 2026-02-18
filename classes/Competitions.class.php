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
      file_put_contents('error_fetching_competition_info.txt', date('d.m.Y G:i') . 'Fetching competition:' . $e->getMessage() . "\n", FILE_APPEND);
    }
    return $competitions;
  }

  public function selectCompetitionLanguage($compId)
  {
    //FETCH COMPETITION LANGUAGE
    try {
      $sql    = ("SELECT competition_language FROM competitions WHERE competition_id = '$compId'");
      $stmt   = $this->connect()->query($sql);
      $l = $stmt->fetchAll();
    } catch (PDOException $e) {
      file_put_contents('error_fetching_language.txt', date('d.m.Y G:i') . 'Fetching competition:' . $e->getMessage() . "\n", FILE_APPEND);
    }
    if ($l[0]["competition_language"] == "FIN") {
      return 0;
    } else if ($l[0]["competition_language"] == "ENG") {
      return 1;
    }
  }


  public function selectCompetitionClasses($competitionId)
  {
  //FETCH COMPETITION CLASSES
    try {
      $sql    = ("SELECT class_id, competition_id, class_name_fin, class_name_eng FROM view_competition_classes WHERE competition_id = $competitionId");
      $stmt   = $this->connect()->query($sql);
      $classes = $stmt->fetchAll();
    } catch (PDOException $e) {
      file_put_contents('error_fetching_competiton_classes.txt', date('d.m.Y G:i') . ' Fetching competition classes:' . $e->getMessage() . "\n", FILE_APPEND);
    }
    return $classes;
  }
}
