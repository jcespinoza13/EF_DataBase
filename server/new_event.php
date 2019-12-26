<?php

session_start();
require('access.php');
require('conector.php');

if (isset($_SESSION['appuser'])) {

  $usenameid = $_SESSION['appuserid'];
  // $_POST['titulo'] = "Guadalupe Reyes1000";
  // $_POST['start_date'] = "2019-12-12";
  // $_POST['start_hour'] = "";
  // $_POST['end_date'] = "";
  // $_POST['end_hour'] = "";
  // $_POST['allDay'] = "true";
  // $data['fk_usuario'] = $usenameid;

  $data['titulo'] = "'".$_POST['titulo']."'";
  $data['fechainicio'] = "'".$_POST['start_date']."'";
  if ($_POST['allDay']){
    $data['horainicio'] = "'00:00'";
    if($_POST['end_date'] != ""){
        $data['fechafin'] = "'".$_POST['end_date']."'";
    } else{
      $data['fechafin'] = "'".$_POST['start_date']."'";
    }
    $data['horafin'] = "'00:00'";
  } else {
    $data['horainicio'] = "'".$_POST['start_hour']."'";
    $data['fechafin'] = "'".$_POST['end_date']."'";
    $data['horafin'] = "'".$_POST['end_hour']."'";
  }
  $data['completo'] = $_POST['allDay'] ;
  $data['fk_usuario'] = $usenameid;


  $con = new ConectorBD($server,$usuario,$pass);

  $response['conexion'] = $con->initConexion($Database);

    if ($response['conexion']=='OK') {
      if($con->insertData('eventos', $data)){
        $response['msg']="OK";
      }else {
        $response['msg']= "Hubo un error y los datos no han sido cargados";
      }
    }else {
      $response['msg']= "No se pudo conectar a la base de datos";
    }

    echo json_encode($response);

  $con->cerrarConexion();


}else {
  header('location: ../client/index.html');
}


 ?>
