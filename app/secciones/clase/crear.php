<?php
include("../../bd.php");
// strtotime('2023-01-01 12:00')
date_default_timezone_set('GMT');

// Create a DateTime object for the current time
$currentDateTime = new DateTime('now');

// Set the desired timezone (GMT-3 in this case)
$timezone = new DateTimeZone('GMT-3');
$currentDateTime->setTimezone($timezone);

// $fch_desde_default =  date('Y-m-d\TH:i', strtotime('now'));
// $fch_hasta_default = strtotime('+1 hour', $fch_desde_default);

// $fch_desde_default =  date('Y-m-d\TH:i', $currentDateTime->getTimestamp());
$fch_desde_default = $currentDateTime->format('Y-m-d\TH:i');
$fch_hasta_default = $currentDateTime->modify('+1 hour')->format('Y-m-d\TH:i');
if ($_POST) {

    if (!empty($_POST)) {
        //checkear valores validos 
        $descripcion = (isset($_POST["descripcion"]) ? $_POST["descripcion"] : "");
        $cant_max_participantes = (isset($_POST["cant_max_participantes"]) ? $_POST["cant_max_participantes"] : "");
        $cant_min_participantes = (isset($_POST["cant_min_participantes"]) ? $_POST["cant_min_participantes"] : "");
        $fch_desde = (isset($_POST["fch_desde"]) ? $_POST["fch_desde"] : "");
        $fch_hasta = (isset($_POST["fch_hasta"]) ? $_POST["fch_hasta"] : "");

        //agregar validacion si esta correcto continuar y ejecutar el insert al final


        $errorDescripcion = $errorCant_max_participantes = $errorCant_min_participantes = $errorFch_desde = $errorFch_hasta = $errorFechas =  false;
        function checkNombre($nombre)
        {
            if (!preg_match("/^['a-zA-Z-' ]*$/", $nombre)) {
                return true;
            } else {
                return false;
            }
        }
        function checkCodigo($codigo)
        {
            if (strlen($codigo) == 0 or strlen($codigo) > 25) {
                return true;
            }
        }
        function checkEmail($email)
        {
            if (!preg_match("/^['a-zA-Z-' ]*$/", $email)) {
                return true;
            }
        }
        function checkFch_hasta($fch_hasta)
        {
            if (!preg_match("/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})(T)([0-1][0-9]|2[0-3])(:)([0-5][0-9])$/", $fch_hasta)) {
                return true;
            }
        }
        function checkFch_desde($fch_desde)
        {
            if (!preg_match("/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})(T)([0-1][0-9]|2[0-3])(:)([0-5][0-9])$/", $fch_desde)) {
                return true;
            }
        }
        function checkFechas($fch_desde, $fch_hasta)
        {
            if ($fch_desde > $fch_hasta) {
                return true;
            }
        }
        // $errorFch_desde = checkFch_desde($fch_desde);
        // $errorFch_hasta = checkFch_hasta($fch_hasta);
        $errorFechas = checkFechas($fch_desde, $fch_hasta);


        if ($errorFechas) {
            $mensaje = "<p>Por favor ingrese un rango de fechas valido. Se ingreso desde: " . $fch_desde . " hasta: " . $fch_hasta . "</p>";
            echo $mensaje;
            // echo "<script language='javascript'>alert('El campo nombre no puede contener numeros ni caracteres especiales. Se ingreso:" . $nombre . "');</script>";
        }
        if (!$errorDescripcion and !$errorCant_max_participantes and !$errorCant_min_participantes and !$errorFch_desde and !$errorFch_hasta and !$errorFechas) {
            $sentencia = $conexion->prepare("INSERT INTO clase(descripcion,cant_max_participantes, cant_min_participantes,fch_desde,fch_hasta) VALUES(:descripcion,:cant_max_participantes,:cant_min_participantes,:fch_desde,:fch_hasta)");

            $sentencia->bindParam(":descripcion", $descripcion);
            $sentencia->bindParam(":cant_max_participantes", $cant_max_participantes);
            $sentencia->bindParam(":cant_min_participantes", $cant_min_participantes);
            $sentencia->bindParam(":fch_desde", $fch_desde);
            $sentencia->bindParam(":fch_hasta", $fch_hasta);
            $sentencia->execute();
            // // Mostrar los mensajes:
            // for ($contador = 0; $contador < count($aMensajes); $contador++)
            //     echo $aMensajes[$contador] . "<br/>";
            echo "<script language='javascript'>alert('Clase agregada');</script>";
        }
    } else {
        echo "<p>No se han especificado todos los datos requeridos.</p>";
    }
}

?>
<?php include("../../templates/header.php"); ?>


<div class="card mt-3 mb-3">
    <div class="card-header bg-secondary text-white">
        Datos de la clase
    </div>
    <div class="card-body bg-dark">

        <form class="" method="post" enctype="multipart/form-data">


            <div class="mb-3 ">
                <label for="descripcion" class="form-label bg-dark text-white">Descripcion</label>
                <input type="text" class="form-control bg-dark text-white" name="descripcion" id="descripcion" aria-describedby="helpId" placeholder="Descripcion" maxlength="50" required>

            </div>


            <div class="mb-3 ">
                <label for="cant_min_participantes" class="form-label bg-dark text-white">Cant min participantes</label>
                <input type="number" class="form-control bg-dark text-white" name="cant_min_participantes" id="cant_min_participantes" aria-describedby="helpId" placeholder="Cant min participantes" value="0" max="2147483647">

            </div>

            <div class="mb-3">
                <label for="cant_max_participantes" class="form-label bg-dark text-white">Cant max participantes</label>
                <input type="number" class="form-control bg-dark text-white" name="cant_max_participantes" id="cant_max_participantes" aria-describedby="helpId" placeholder="Cant max participantes" required value="99" max="2147483647">

            </div>

            <div class="mb-3">
                <label for="fch_desde" class="form-label bg-dark text-white">Fecha Desde</label>
                <input type="datetime-local" class="form-control bg-dark text-white" name="fch_desde" id="fch_desde" aria-describedby="helpId" placeholder="Fecha Desde" value="<?php echo $fch_desde_default; ?>" required>

            </div>
            <div class="mb-3">
                <label for="fch_hasta" class="form-label bg-dark text-white">Fecha Hasta</label>
                <input type="datetime-local" class="form-control bg-dark text-white" name="fch_hasta" id="fch_hasta" aria-describedby="helpId" placeholder="Fecha Hasta" value="<?php echo $fch_hasta_default; ?>" required>

            </div>





            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>
</div>
<?php include("../../templates/footer.php"); ?>
<?php include("../../templates/footer.php"); ?>