<?php 
interface RegistrationModelInterface {
  public function login($login, $password);
  public function registration($login, $password);
  public function getUserIdByLogin($login);
}

?>