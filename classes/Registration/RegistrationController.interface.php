<<?php
interface RegistrationControllerInterface {
  public function __construct();
  public function login($login, $password);
  public function registration($login, $password);
}

?>