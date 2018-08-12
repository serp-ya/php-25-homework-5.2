<?php
require_once('classes/Controller.abs.class.php');
require_once('classes/Registration/RegistrationModel.class.php');
require_once('classes/Registration/RegistrationController.interface.php');

try {

  class RegistrationController extends Controller implements RegistrationControllerInterface {
    
    public function __construct() 
    {
      $this->sqlConnection = new RegistrationModel();
    }
  
    public function login($login, $password)
    {
      $result = $this->sqlConnection->login($login, $password);
  
      if (gettype($result) !== 'array') {
        return [
          'error' => 'Не правильно введён логин или пароль или такого пользователя не существует',
        ];
      }
  
      return $result;
    }
  
    public function registration($login, $password)
    {
      $userExist = $this->sqlConnection->getUserIdByLogin($login);
  
      if (gettype($userExist) === 'array') {
        return [
          'error' => 'Пользователь с таким логином уже существует!',
        ];
      }
  
      return $this->sqlConnection->registration($login, $password);
    }
  }
} catch (Exception $error) {
  exit('Error: ' . $error->getMessage());
}

?>
