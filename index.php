<?php
date_default_timezone_set('UTC');
require_once('./vendor/autoload.php');
session_start();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader, array(
  'cache' => './tmp/cache',
  'auto_reload' => true,
));
$templateParams = [
  'userId' => '',
  'isEdit' => false,
  'editedId' => '',
  'editedDescription' => '',
  'usersDataOptionsHtml' => '',
  'taskData' => [],
  'assignedData' => [],
];

if (!empty($_SESSION['userid'])) {
  require_once('classes/App/AppController.class.php');
  $templateParams['userId'] = $_SESSION['userid'];
  $userId = $templateParams['userId'];
  $controller = new AppController();
  $templateParams['taskData'] = $controller->getData($userId);
  $templateParams['assignedData'] = $controller->getAssignedTasks($userId);
  $templateParams['usersData'] = $controller->getUsersList();
  $templateParams['isEdit'] = false;

  if (!empty($_GET['action'])) {
    switch($_GET['action']) {
      case 'change': {
        $templateParams['isEdit'] = true;
        $templateParams['editedId'] = $_GET['id'];
        break;
      }

      case 'exit': {
        session_destroy();
        header('Location: ./');
        break;
      }
    }
  }

  if ($templateParams['isEdit']) {
    foreach ($templateParams['taskData'] as $task) {
      if ((int) $task['id'] === (int) $templateParams['editedId']) {
        $templateParams['editedDescription'] = $task['description'];
      }
    }
  }

  foreach ($templateParams['usersData'] as $user) {
    $templateParams['usersDataOptionsHtml'] .= '<option value="' 
    . $user['id'] 
    . '">'
    . $user['login']
    . '</option> \n';
  }
}

echo $twig->render('app.twig', $templateParams);

?>
