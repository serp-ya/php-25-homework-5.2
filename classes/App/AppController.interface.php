<?php
interface AppControllerInterface {
  public function __construct();
  public function getData($userId);
  public function getAssignedTasks($userId);
  public function getUsersList();
}

?>
