<?php
if (!empty($_SESSION['userid'])) {
  redirectToMain();
}

$emptyFields = null;

if (!empty($_POST['action'])) {
  if (!empty($_POST['login']) || !empty($_POST['password'])) {
    require_once('classes/Registration/RegistrationController.class.php');
    session_start();

    $controller = new RegistrationController();
    $action = $_POST['action'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $userData;

    switch($action) {
      case 'Вход': {
        $userData = $controller->login($login, $password);
        break;
      }
      
      case 'Регистрация': {
        $userData = $controller->registration($login, $password);
        break;
      }
    }

    if (!empty($userData['id'])) {
      $_SESSION['userid'] = (int) $userData['id'];
      $_SESSION['userlogin'] = (string) $userData['login'];
      redirectToMain();
    }

  } else {
    $emptyFields = true;
  }
}

$inputDefaultCssClasses = 'input';
$inputCssClasses = $emptyFields ? "$inputDefaultCssClasses input_type_danger" : $inputDefaultCssClasses;

function redirectToMain() {
  header('Location: ./');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Войти на сайт</title>
</head>
<body>
  <p>Введите данные для входа или регистрации:</p>

  <?php if($emptyFields): ?>
    <p style="color: red">Оба поля "Логин" и "Пароль" обязательны</p>
  <?php elseif(!empty($userData['error'])): ?>
    <p style="color: red"><?php echo $userData['error']; ?></p>
  <?php endif; ?>

  <form class="form" method="POST">

    <input class="<?php echo $inputCssClasses; ?>" type="text" name="login" placeholder="Логин">
    <br />
    <input class="<?php echo $inputCssClasses; ?>" type="password" name="password" placeholder="Пароль">
    <br />
    <input class="<?php echo $inputDefaultCssClasses; ?>" type="submit" name="action" value="Вход">
    <br />
    <input class="<?php echo $inputDefaultCssClasses; ?>" type="submit" name="action" value="Регистрация">

  </form>
</body>

<style>
.form {
  display: flex;
  flex-flow: column;
}

.input {
  max-width: 200px;
}

.input_type_danger {
  border-color: red;
}
</style>
</html>