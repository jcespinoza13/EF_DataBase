<?php
session_start();
require('access.php');
require('conector.php');

if (isset($_SESSION['appuser'])) {

  $usernameid = $_SESSION['appuserid'];
  // $usernameid = 17;

  $con = new ConectorBD($server,$usuario,$pass);

    if ($con->initConexion($Database)=='OK') {
      $conexion = $con->getConexion();
      // print "Conexión Exitosa";

      $resultado_consulta = $con->consultar(['eventos'],
      ['id', 'titulo as title', 'DATE_FORMAT(concat(fechaInicio,"T",horainicio,"Z"), "%Y-%m-%dT%TZ") as start', 'DATE_FORMAT(concat(fechafin,"T",horafin,"Z"), "%Y-%m-%dT%TZ") as end', 'CASE WHEN completo = 1 THEN "true" ELSE "false" END as allDay', 'fk_usuario as resourceId' ], 'WHERE fk_usuario='.$usernameid);
      // ['titulo', 'fechainicio'], 'WHERE fk_usuario='.$usernameid);

    if ($resultado_consulta->num_rows != 0) {

      $i=0;
      while ($fila = $resultado_consulta->fetch_assoc()) {
        $response['eventos'][$i]['id']=$fila['id'];
        $response['eventos'][$i]['title']=$fila['title'];
        $response['eventos'][$i]['start']=$fila['start'];
        $response['eventos'][$i]['end']=$fila['end'];
        $response['eventos'][$i]['allDay']=$fila['allDay'];
        $response['eventos'][$i]['resourceId']=$fila['resourceId'];
        $i++;
      }
      $response['msg'] = "OK";

    }else {
      $response['msg'] = 'No hay eventos';
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
