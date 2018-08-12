<?php
interface AppModelInterface
{
  public function getTasks($userId, $sortBy, $mode);
  public function getUsersList();
  public function addNewTask($userId, $newTask);
  public function changeTaskDescription($id, $description);
  public function newAssignTask($taskId, $assignedUser);
  public function doneTask($id);
  public function deleteTask($id);
}

?>
