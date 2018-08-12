<?php
if (!empty($_SESSION['userid'])) {
  redirectToMain();
}

require_once('./vendor/autoload.php');

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader, array(
  'cache' => './tmp/cache',
  'auto_reload' => true,
));
$templateParams = [
  'emptyFields' => false,
  'userData' => [],
  'inputCssClasses' => ''
];

if (!empty($_POST['action'])) {
  if (!empty($_POST['login']) || !empty($_POST['password'])) {
    require_once('classes/Registration/RegistrationController.class.php');
    session_start();

    $controller = new RegistrationController();
    $action = $_POST['action'];
    $login = $_POST['login'];
    $password = $_POST['password'];

    switch($action) {
      case 'Вход': {
        $templateParams['userData'] = $controller->login($login, $password);
        break;
      }
      
      case 'Регистрация': {
        $templateParams['userData'] = $controller->registration($login, $password);
        break;
      }
    }

    if (!empty($templateParams['userData']['id'])) {
      $_SESSION['userid'] = (int) $templateParams['userData']['id'];
      $_SESSION['userlogin'] = (string) $templateParams['userData']['login'];
      redirectToMain();
    }

  } else {
    $templateParams['emptyFields'] = true;
  }
}
$templateParams['inputDefaultCssClasses'] = 'input';
$inputDefaultCssClasses = $templateParams['inputDefaultCssClasses'];
$emptyFields = $templateParams['emptyFields'];
$inputCssClasses = $emptyFields ? "$inputDefaultCssClasses input_type_danger" : $inputDefaultCssClasses;
$templateParams['inputCssClasses'] = $inputCssClasses;

function redirectToMain() {
  header('Location: ./');
}

echo $twig->render('registration.twig', $templateParams);

?>
