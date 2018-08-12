<?php
require_once('classes/Model.abs.class.php');
require_once('classes/Registration/RegistrationModel.interface.php');

try {

  class RegistrationModel extends Model implements RegistrationModelInterface {
    private $usersTableName = 'user';
    private $crudeLogin;
    private $crudePassword;

    public function login($login, $password)
    {
      $crudeLogin = (string) strip_tags($login);
      $crudePassword = (string) strip_tags($password);
      $query = $this->prepareLoginUserQuery($crudeLogin, md5($crudePassword));
      $stmt = $this->executeQuery($query);
      return $stmt->fetch();
    }

    private function prepareLoginUserQuery($login, $password)
    {
      return (
        "SELECT id 
         FROM $this->usersTableName 
         WHERE login='$login' AND password='$password'
        "
      );
    }

    public function registration($login, $password)
    {
      $crudeLogin = (string) strip_tags($login);
      $crudePassword = (string) strip_tags($password);
      $query = $this->prepareRegistrationUserQuery($crudeLogin, md5($crudePassword));
      $stmt = $this->executeQuery($query);
      return [
        'id' => $this->db->lastInsertId(),
        'login' => $crudeLogin,
      ];
    }

    private function prepareRegistrationUserQuery($login, $password)
    {
      return (
        "INSERT INTO $this->usersTableName (login, password)
         VALUES ('$login', '$password')
        "
      );
    }

    public function getUserIdByLogin($login)
    {
      $crudeLogin = (string) strip_tags($login);
      $query = $this->prepareGetUserByLoginQuery($crudeLogin);
      $stmt = $this->executeQuery($query);
      return $stmt->fetch();
    }

    private function prepareGetUserByLoginQuery($login)
    {
      return (
        "SELECT id 
         FROM $this->usersTableName 
         WHERE login='$login'
        "
      );
    }
  }
} catch (Exception $error) {
  exit('Error: ' . $error->getMessage());
}