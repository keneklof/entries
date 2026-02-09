<?php
class Database
{
  private $user   = "xosiponx_kenny";
  private $host   = "mysql07.domainhotelli.fi";
  private $pwd    = "s*)r)K?yANjH";
  private $dbName = "xosiponx_enrollments";

  public function connect()
  {
    $pdo = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbName . '; charset=utf8', $this->user, $this->pwd);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  }
}
