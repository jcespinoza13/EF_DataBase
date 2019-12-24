<?php

require('access.php');
require('conector.php');

$con = new ConectorBD($server,$usuario,$pass);

  if ($con->initConexion($Database)=='OK') {
    $conexion = $con->getConexion();
    print "Conexión Exitosa";

      $resultado_consulta = $con->consultar(['usuarios'],
      ['usuario', 'password'], 'WHERE id > 0');


      if ($resultado_consulta->num_rows <= 0) {
        //Extraer datos del json para crerar usuarios si no hay ninguno.
        $data_file = fopen("../client/data/datos.json","r");
        $data_readed = fread($data_file, filesize("../client/data/datos.json"));
        $data = json_decode($data_readed, true);
        fclose($data_file);
        // $datos = array();

        foreach ($data as $data) {
          // code...
          $text = '';
          $fecha = '';

            foreach ($data as $key => $value) {
              // echo '<pre>';
              // $text = $text ."<strong>" .$key .": </strong>" .$value . "<br>";

            if($key == "usuario") {
                $datos['usuario'] = "'".$value ."'";
              }
            if($key == "nombre") {
                $datos['nombre'] = "'".$value ."'";
              }
            if($key == "password") {
                $datos['password'] = $value;
              }
            if($key == "fechaNac") {
                $fecha = date_create_from_format("d/m/Y",$value);
                $datos['fechaNac'] = "'" .date_format($fecha,"Y-m-d") ."'";
                // $datos['fechaNac'] = "'" .date_format(date_create($value),"Y-m-d") ."'";

                // print_r($datos['fechaNac']);


                if ($con->insertData('usuarios', $datos)) {
                  echo "Se insertaron los datos correctamente";
                }else echo "Se ha producido un error en la inserción";

              }
          }
        }
        // print_r($datos);

      }



    $con->cerrarConexion();

}else {
  echo "Se presentó un error en la conexión";
  $con->cerrarConexion();
}
 ?>
