<?php
require('access.php');
require('conector.php');

// $_POST['username'] = 'Pedro';
// $_POST['password'] = '12345';
$username = $_POST['username'];
$passw = $_POST['password'];

$con = new ConectorBD($server,$usuario,$pass);

  if ($con->initConexion($Database)=='OK') {
    $conexion = $con->getConexion();
    // print "Conexión Exitosa";

    $resultado_consulta = $con->consultar(['usuarios'],
    ['id','usuario', 'password'], 'WHERE usuario="'.$username.'" and password= sha1("'.$passw.'")');

  if ($resultado_consulta->num_rows != 0) {
    $fila = $resultado_consulta->fetch_assoc();
      $response['acceso'] = 'concedido';
      $response['motivo'] = 'Credenciales correctas';
      // $acceso = 'concedido';
      session_start();
      $_SESSION['appuser']=$username;
      $_SESSION['appuserid']=$fila['id'];
      $response['user'] = $_SESSION['appuser'];
      $response['id'] = $_SESSION['appuserid'];

  }else {
    $response['acceso'] = 'rechazado';
    $response['motivo'] = 'Credenciales incorrectas';
    // $acceso = 'rechazado';
  }
  // return $acceso;
}

echo json_encode($response);
// echo($acceso);

$con->cerrarConexion();




 ?>
