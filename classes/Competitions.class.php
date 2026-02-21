<?php

class Competitions extends Database
{
  public $competitionId;

  public function selectCompetitionInfo($competitionId)
  {
    $this->competitionId = $competitionId;

    //FETCH COMPETITION INFO
    try {
      $sql    = ("SELECT * FROM competitions WHERE competition_id = '$competitionId'");
      $stmt   = $this->connect()->query($sql);
      $competitions = $stmt->fetchAll();
    } catch (PDOException $e) {
      file_put_contents('error_fetching_competition_info.txt', date('d.m.Y G:i') . 'Fetching competition:' . $e->getMessage() . "\n", FILE_APPEND);
    }
    return $competitions;
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

  private function checkLanguage($competitionId)
  {
    //FETCH COMPETITION LANGUAGE
    try {
      $sql    = ("SELECT competition_language FROM  competitions WHERE competition_id = $competitionId");
      $stmt   = $this->connect()->query($sql);
      $language = $stmt->fetchColumn();
    } catch (PDOException $e) {
      file_put_contents('error_fetching_competiton_language_private.txt', date('d.m.Y G:i') . ' Fetching competition language:' . $e->getMessage() . "\n", FILE_APPEND);
    }
    return $language;
  }

  public function selecPilotsCountries($competitionId)
  {
    $language = $this->checkLanguage($competitionId);

    //FETCH COMPETITION PILOTS COUNTRIES
    if ($language == "FIN") {
      try {
        $sql    = ("SELECT * FROM  view_competition_countries WHERE competition_id = $competitionId ORDER BY country_name_fin");
        $stmt   = $this->connect()->query($sql);
        $countries = $stmt->fetchAll();
      } catch (PDOException $e) {
        file_put_contents('error_fetching_competiton_countries.txt', date('d.m.Y G:i') . ' Fetching competition countries:' . $e->getMessage() . "\n", FILE_APPEND);
      }
      return $countries;
    } else if ($language == "ENG") {
      try {
        $sql    = ("SELECT * FROM  view_competition_countries WHERE competition_id = $competitionId ORDER BY country_name_eng");
        $stmt   = $this->connect()->query($sql);
        $countries = $stmt->fetchAll();
      } catch (PDOException $e) {
        file_put_contents('error_fetching_competiton_countries.txt', date('d.m.Y G:i') . ' Fetching competition countries:' . $e->getMessage() . "\n", FILE_APPEND);
      } 
      return $countries;
    }
  }
}
