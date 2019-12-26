
<?php
require('access.php');
require('conector.php');

crearDB($server,$usuario,$pass,$Database);
function crearDB($server,$usuario,$pass,$Database){
  $conexion = new mysqli($server, $usuario, $pass);

    if ($conexion->connect_error) {
      $response['msg'] = "Error:" . $conexion->connect_error;
    }

    $sql = 'CREATE DATABASE IF NOT EXISTS `db_ef_nextu`';

    if ($conexion->query($sql) === TRUE) {
      $response['msg'] = "La base de datos db_ef_nextu se creó exitosamente";
      tablaUsuarios($server,$usuario,$pass,$Database);
    }else {
      $response['msg'] = "Se presentó un error ".$conexion->error;
    }

  $conexion->close();
  echo json_encode($response);
}

function tablaUsuarios($server,$usuario,$pass,$Database){

  $nombreTabla = 'usuarios';
  $campos['id']= 'INT PRIMARY KEY NOT NULL AUTO_INCREMENT';
  $campos['usuario']= 'VARCHAR(50) NOT NULL';
  $campos['nombre']= 'VARCHAR(150) NOT NULL';
  $campos['password']= 'char(255) NOT NULL';
  $campos['fechaNac']= 'date  NOT NULL';

  $con = new ConectorBD($server,$usuario,$pass);

  if ($con->initConexion($Database)=='OK') {
    if ($con->newTable($nombreTabla, $campos)) {
      $response['msg'] = "Se creó exitosamente, la tabla " .$nombreTabla ;
      tablaEventos($server,$usuario,$pass,$Database);
    }else {
      $response['msg'] = "Se produjo un error al crear la tabla " .$nombreTabla;
    }
  }else {
    $response['msg'] = "Se produjo un error en la conexión";
  }

  $con->cerrarConexion();
  echo json_encode($response);
}

function tablaEventos($server,$usuario,$pass,$Database){

    $nombreTabla = 'eventos';
    $campos['id']= 'INT PRIMARY KEY NOT NULL AUTO_INCREMENT';
    $campos['titulo']= 'VARCHAR(50) NOT NULL';
    $campos['fechaInicio']= 'date NOT NULL';
    $campos['fechaFin']= 'date NOT NULL';
    $campos['HoraInicio']= 'time NOT NULL';
    $campos['HoraFin']= 'time NOT NULL';
    $campos['completo']= 'tinyint(1) NOT NULL';
    $campos['fk_usuario']= 'INT(11) NOT NULL';


    $con = new ConectorBD($server,$usuario,$pass);

    if ($con->initConexion($Database)=='OK') {
      if ($con->newTable($nombreTabla, $campos)) {
          $response['msg'] = "Se creó exitosamente, la tabla " .$nombreTabla ;
          addConstraints($server,$usuario,$pass,$Database);
      }else {
          $response['msg'] = "Se produjo un error al crear la tabla " .$nombreTabla;
        }
      }else {
          $response['msg'] = "Se produjo un error en la conexión";
        }

    $con->cerrarConexion();
    echo json_encode($response);
  }

  function addConstraints($server,$usuario,$pass,$Database){
    $con = new ConectorBD($server,$usuario,$pass);

    if ($con->initConexion($Database)=='OK') {

      $sql = 'ALTER TABLE `eventos` ADD CONSTRAINT usuarios_eventos FOREIGN KEY (fk_usuario) REFERENCES usuarios (id)';
      // $sql = 'USE db_ef_nextu';

      if ($con->ejecutarQuery($sql) === TRUE) {
        $response['msg'] =  "Las restricciones se crearon exitosamente";
        crearusuarios($server,$usuario,$pass,$Database);
      }else {
        $response['msg'] = "Se presentó un error ".$sql;
      }

      $con->cerrarConexion();
    }
    echo json_encode($response);
  }

function crearusuarios($server,$usuario,$pass,$Database){
  $con = new ConectorBD($server,$usuario,$pass);

    if ($con->initConexion($Database)=='OK') {
      $conexion = $con->getConexion();
      // print "Conexión Exitosa";

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
                    $response['msg'] =  "Se insertaron los datos de usuarios correctamente";
                  }else $response['msg'] = "Se ha producido un error en la inserción de usuarios";

                }
            }
          }
          creareventos($server,$usuario,$pass,$Database);
        } else {
            $response['msg'] = "Datos ya existen";
            creareventos($server,$usuario,$pass,$Database);
        }

  }else {
    $response['msg'] = "Se presentó un error en la conexión";

  }
  $con->cerrarConexion();
  echo json_encode($response);
}

function creareventos($server,$usuario,$pass,$Database){
  $con = new ConectorBD($server,$usuario,$pass);

    if ($con->initConexion($Database)=='OK') {
      $conexion = $con->getConexion();
      // print "Conexión Exitosa";

        $resultado_consulta = $con->consultar(['eventos'],
        ['fk_usuario'], 'WHERE id > 0');


        if ($resultado_consulta->num_rows <= 0) {
          //Extraer datos del json para crerar usuarios si no hay ninguno.
          $data_file = fopen("../client/data/eventos.json","r");
          $data_readed = fread($data_file, filesize("../client/data/eventos.json"));
          $data = json_decode($data_readed, true);
          fclose($data_file);
          // $datos = array();

          foreach ($data as $data) {
            // code...
            $text = '';
            $fecha = '';

              foreach ($data as $key => $value) {

              if($key == "titulo") {
                  $datos['titulo'] = "'".$value ."'";
                }
              if($key == "fechaInicio") {
                $fechaI = date_create_from_format("d/m/Y",$value);
                $datos['fechaInicio'] = "'" .date_format($fechaI,"Y-m-d") ."'";
                }
              if($key == "fechaFin") {
                $fechaF = date_create_from_format("d/m/Y",$value);
                $datos['fechaFin'] = "'" .date_format($fechaF,"Y-m-d") ."'";
                }
              if($key == "horaInicio") {
                $horaI = date_create_from_format("H:i",$value);
                $datos['horaInicio'] = "'" .date_format($horaI,"H:i") ."'";
                }
              if($key == "horaFin") {
                $horaF = date_create_from_format("H:i",$value);
                $datos['horaFin'] = "'" .date_format($horaF,"H:i") ."'";
                }

              if($key == "completo") {
                  $datos['completo'] = $value;
                }
              if($key == "fk_usuario") {
                  $datos['fk_usuario'] = $value;
                  // $datos['fechaNac'] = "'" .date_format(date_create($value),"Y-m-d") ."'";

                  // print_r($datos['fechaNac']);


                  if ($con->insertData('eventos', $datos)) {
                    $response['msg'] =  "Se insertaron los datos de eventos correctamente";
                  }else $response['msg'] = "Se ha producido un error en la insersión de eventos";

                }
            }
          }
          // print_r($datos);
        } else {
            $response['msg'] = "Datos ya existen";
        }

  }else {
    $response['msg'] = "Se presentó un error en la conexión";

  }
  $con->cerrarConexion();
  echo json_encode($response);
}
 ?>
