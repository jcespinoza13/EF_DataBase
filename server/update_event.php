<?php

session_start();
require('access.php');
require('conector.php');

if (isset($_SESSION['appuser'])) {

  // $_POST['id'] = 74;
  // $_POST['start_date'] = '2019-12-23';
  // $_POST['end_date'] = '2019-12-23';
  // $_POST['start_hour'] = '00:00';
  // $_POST['end_hour'] = '23:59';

  $sentencia = 'id';
  $eventid = $_POST['id'];
  $condicion = $sentencia ." = ".$eventid;
  $data['fechainicio'] = "'" .date_format(date_create($_POST['start_date']),"Y-m-d") ."'";
  // if ($_POST['allDay']){
  //   $data['horainicio'] = "'00:00'";
  //   if($_POST['end_date'] != ""){
  //       $data['fechafin'] = "'".$_POST['end_date']."'";
  //   } else{
  //     $data['fechafin'] = "'" .date_format(date_create($_POST['start_date']),"Y-m-d") ."'";
  //   }
  //   $data['horafin'] = "'00:00'";
  // } else {
  //   $data['fechafin'] = "'" .date_format(date_create($_POST['end_date']),"Y-m-d") ."'";
  //   $data['horainicio'] = "'" .date_format(date_create($_POST['start_hour']),"H:i") ."'";
  //   $data['horafin'] = "'" .date_format(date_create($_POST['end_hour']),"H:i") ."'";
  // }

$data['fechafin'] = "'" .date_format(date_create($_POST['end_date']),"Y-m-d") ."'";
$data['horainicio'] = "'" .date_format(date_create($_POST['start_hour']),"H:i") ."'";
$data['horafin'] = "'" .date_format(date_create($_POST['end_hour']),"H:i") ."'";


  $con = new ConectorBD($server,$usuario,$pass);

    if ($con->initConexion($Database)=='OK') {
      $conexion = $con->getConexion();
    if ($con->actualizarRegistro('eventos', $data, $condicion)) {
      // $response['resultado'] = $con->actualizarRegistro('eventos', $data, $condicion);
      $response['msg'] = "OK";

    }else {
      $response['msg'] = 'No se pudo actualizar el evento';
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
