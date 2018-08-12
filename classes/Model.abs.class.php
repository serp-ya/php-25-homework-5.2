<?php
abstract class Model {
  protected $db;
  protected $config;

  public function __construct() 
  {
    $configJson = file_get_contents('config.json');
    $this->config = json_decode($configJson, true);

    $userName = $this->config['username'];
    $password = $this->config['password'];

    $connectQuery = $this->getConnectQuery();
    $this->db = new PDO($connectQuery, $userName, $password);
  }

  protected function getConnectQuery() 
  {
    $serverName = $this->config['server'];
    $dbName = $this->config['dbName'];
    return "mysql:host={$serverName};dbname={$dbName}";
  }

  protected function executeQuery($query)
  {
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  protected function transformStmtToArray($stmt)
  {
    $response = [];

    while($row = $stmt->fetch()) {
      $response[] = $row;
    }

    return $response;
  }
}

?>