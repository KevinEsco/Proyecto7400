<?php
include("../../bd.php");

if ($_POST) {
    if (!empty($_POST)) {
    } else {
        echo "<p>No se han especificado todos los datos requeridos.</p>";
    }

    // Si han habido errores se muestran, sino se mostrán los mensajes
    if (isset($aErrores)) {
        if (count($aErrores) > 0) {
            # code...
            $mensaje = "ERRORES ENCONTRADOS: " . "\n";


            // Mostrar los errores:
            for ($contador = 0; $contador < count($aErrores); $contador++)
                $mensaje .= $aErrores[$contador] . "\n";
            echo $mensaje;
        }
    } else {





        $descripcion = (isset($_POST["descripcion"]) ? $_POST["descripcion"] : "");
        $cant_max_participantes = (isset($_POST["cant_max_participantes"]) ? $_POST["cant_max_participantes"] : "");
        $cant_min_participantes = (isset($_POST["cant_min_participantes"]) ? $_POST["cant_min_participantes"] : "");
        $fch_desde = (isset($_POST["fch_desde"]) ? $_POST["fch_desde"] : "");
        $fch_hasta = (isset($_POST["fch_hasta"]) ? $_POST["fch_hasta"] : "");
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
                <input type="text" class="form-control bg-dark text-white" name="descripcion" id="descripcion" aria-describedby="helpId" placeholder="Descripcion" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>


            <div class="mb-3 ">
                <label for="cant_min_participantes" class="form-label bg-dark text-white">Cant min participantes</label>
                <input type="number" class="form-control bg-dark text-white" name="cant_min_participantes" id="cant_min_participantes" aria-describedby="helpId" placeholder="Cant min participantes">
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>

            <div class="mb-3">
                <label for="cant_max_participantes" class="form-label bg-dark text-white">Cant max participantes</label>
                <input type="number" class="form-control bg-dark text-white" name="cant_max_participantes" id="cant_max_participantes" aria-describedby="helpId" placeholder="Cant max participantes" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>

            <div class="mb-3">
                <label for="fch_desde" class="form-label bg-dark text-white">Fecha Desde</label>
                <input type="datetime-local" class="form-control bg-dark text-white" name="fch_desde" id="fch_desde" aria-describedby="helpId" placeholder="Fecha Desde" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="fch_hasta" class="form-label bg-dark text-white">Fecha Hasta</label>
                <input type="datetime-local" class="form-control bg-dark text-white" name="fch_hasta" id="fch_hasta" aria-describedby="helpId" placeholder="Fecha Hasta" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>





            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>
</div>
<?php include("../../templates/footer.php"); ?>
<?php include("../../templates/footer.php"); ?>