<?php

class Conexion extends PDO
{
  public function __construct()
  {
    try {
      parent::__construct('mysql:host=localhost;port=3306;dbname=seguridadBD', 'root', '',
        array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        //echo json_encode(['Exito' => 'Conectado']);
      } catch (Exception $ex) {
      //echo json_encode(['Error' => 'Error al conectar']);
    }
  }
}