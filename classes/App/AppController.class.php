<?php
require_once('classes/Controller.abs.class.php');
require_once('classes/App/AppModel.class.php');
require_once('classes/App/AppController.interface.php');

try {

  class AppController extends Controller implements AppControllerInterface {
    private $newTaskDescription;
    private $newDescription;
    private $changedTaskId;
    private $assignedUser;
    private $sortBy;
    private $taskId;
    private $action;

    public function __construct() 
    {
      $this->sqlConnection = new AppModel();

      if (!empty($_POST['new-task'])) {
        $this->newTaskDescription = (string) $_POST['new-task'];

      } else if (!empty($_POST['task_id']) && !empty($_POST['assigned_user'])) {
        $this->changedTaskId = (int) $_POST['task_id'];
        $this->assignedUser = (string) $_POST['assigned_user'];

      } else if (!empty($_POST['sort-by'])) {
        $this->sortBy = (string) $_POST['sort-by'];

      } else if (!empty($_POST['description'])) {
        $this->newDescription = (string) $_POST['description'];

      } else if (!empty($_POST['id'])) {
        $this->taskId = (string) $_POST['id'];
      }
      
      if (!empty($_GET['id']) && !empty($_GET['action'])) {
        $this->taskId = (int) $_GET['id'];
        $this->action = (string) $_GET['action'];
      }    
    }

    public function getData($userId) 
    {
      $newTaskDescription = $this->newTaskDescription;
      $sortBy = $this->sortBy ? $this->sortBy : 'id';
      $newDescription = $this->newDescription;
      $changedTaskId = $this->changedTaskId;
      $assignedUser = $this->assignedUser;

      $action = $this->action;
      $taskId = $this->taskId;

      if ($newTaskDescription) {
        $this->sqlConnection->addNewTask($userId, $newTaskDescription);
      }
      
      if ($newDescription) {
        $this->sqlConnection->changeTaskDescription($taskId, $newDescription);
        header("Location: ./");
      }

      if ($changedTaskId && $assignedUser) {
        $this->sqlConnection->newAssignTask($changedTaskId, $assignedUser);
      }

      switch($action) {      
        case 'done': {
          $this->sqlConnection->doneTask($taskId);
          break;
        }
        
        case 'delete': {
          $this->sqlConnection->deleteTask($taskId);
          break;
        }
      }

      return $this->sqlConnection->getTasks($userId, $sortBy);
    }

    public function getAssignedTasks($userId)
    {
      return $this->sqlConnection->getTasks($userId, 'id', 'assigned');
    }

    public function getUsersList() {
      return $this->sqlConnection->getUsersList();
    }
  }
} catch (Exception $error) {
  exit('Error: ' . $error->getMessage());
}

?>
