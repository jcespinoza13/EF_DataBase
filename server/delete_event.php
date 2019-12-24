<?php

session_start();
require('access.php');
require('conector.php');

if (isset($_SESSION['appuser'])) {

  $eventid = $_POST['id'];

  $con = new ConectorBD($server,$usuario,$pass);

    if ($con->initConexion($Database)=='OK') {
      $conexion = $con->getConexion();
      // print "Conexión Exitosa";

      $resultado_consulta = $con->eliminarRegistro('eventos',
      'id ='.$eventid);

    if ($resultado_consulta) {
      $response['msg'] = "OK";

    }else {
      $response['msg'] = 'No se pudo borrar el evento';
      // $acceso = 'rechazado';
    }
    echo json_encode($response);
  }


  // echo($acceso);

  $con->cerrarConexion();


}else {
  header('location: ../client/index.html');
}



 ?>
