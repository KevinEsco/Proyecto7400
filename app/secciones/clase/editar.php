<?php
include("../../bd.php");
if (isset($_GET['ID'])) {
    # code...
    $ID = (isset($_GET['ID'])) ? $_GET['ID'] : "";
    $sentencia = $conexion->prepare("SELECT * FROM clase where id_clase = :ID");
    $sentencia->bindParam(":ID", $ID);
    $sentencia->execute();

    $clase = $sentencia->fetch(PDO::FETCH_LAZY);
}

if ($_POST) {
    if (!empty($_POST)) {
        // Comprobar si llegaron los campos requeridos:
        if (isset($_POST['cod_clase']) && isset($_POST['desc_corta']) && isset($_POST['desc_larga']) && isset($_POST['cant_clases'])) {
            // cod_clase:
            if (empty($_POST['cod_clase']))
                $aErrores[] = "Debe especificar el cod_clase";
            else {
                // Comprobar mediante una expresión regular, que sólo contiene letras y espacios:
                // if (preg_match($patron_texto, $_POST['txtNombre']))
                //     $aMensajes[] = "Nombre: [" . $_POST['txtNombre'] . "]";
                // else
                //     $aErrores[] = "El cod_clase sólo puede contener letras y espacios";
            }

            // desc_corta:
            if (empty($_POST['desc_corta']))
                $aErrores[] = "Debe especificar desc_corta";
            else {
                // Comprobar mediante una expresión regular, que sólo contienen letras y espacios:
                // if (preg_match($patron_texto, $_POST['desc_corta']))
                //     $aMensajes[] = "desc_corta: [" . $_POST['desc_corta'] . "]";
                // else
                //     $aErrores[] = "Los apellidos sólo pueden contener letras y espacios";
            }

            // // desc_larga:
            // if (empty($_POST['desc_larga']))
            //     $aErrores[] = "Debe especificar los apellidos";
            // else {
            //     // Comprobar mediante una expresión regular, que sólo contienen letras y espacios:
            //     // if (preg_match($patron_texto, $_POST['desc_corta']))
            //     //     $aMensajes[] = "desc_corta: [" . $_POST['desc_corta'] . "]";
            //     // else
            //     //     $aErrores[] = "Los apellidos sólo pueden contener letras y espacios";
            // }

            // cant_clases:

            if (empty($_POST['cant_clases']))
                $aErrores[] = "Debe especificar cant_clases";
            else {
                if ((isset($_POST['cant_clases'])) && (!empty($_POST['cant_clases']))) {
                    if (is_numeric($_POST['cant_clases']))
                        $aMensajes[] = "Edad: [" . $_POST['cant_clases'] . "]";
                    else
                        $aErrores[] = "El campo cant_clases debe contener un número.";
                }
            }
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
            $ID = (isset($_POST['ID'])) ? $_POST['ID'] : "";
            $descripcion = (isset($_POST["descripcion"]) ? $_POST["descripcion"] : "");
            $cant_max_participantes = (isset($_POST["cant_max_participantes"]) ? $_POST["cant_max_participantes"] : "");
            $cant_min_participantes = (isset($_POST["cant_min_participantes"]) ? $_POST["cant_min_participantes"] : "");
            $fch_desde = (isset($_POST["fch_desde"]) ? $_POST["fch_desde"] : "");
            $fch_hasta = (isset($_POST["fch_hasta"]) ? $_POST["fch_hasta"] : "");
            $sentencia = $conexion->prepare("UPDATE clase SET descripcion =:descripcion,cant_max_participantes=:cant_max_participantes,cant_min_participantes=:cant_min_participantes,fch_desde=:fch_desde,fch_hasta=:fch_hasta WHERE id_clase = :ID");

            $sentencia->bindParam(":descripcion", $descripcion);
            $sentencia->bindParam(":cant_max_participantes", $cant_max_participantes);
            $sentencia->bindParam(":cant_min_participantes", $cant_min_participantes);
            $sentencia->bindParam(":fch_desde", $fch_desde);
            $sentencia->bindParam(":fch_hasta", $fch_hasta);
            $sentencia->bindParam(":ID", $ID);
            $sentencia->execute();
            // // Mostrar los mensajes:
            // for ($contador = 0; $contador < count($aMensajes); $contador++)
            // //     echo $aMensajes[$contador] . "<br/>";
            // echo "<script language='javascript'>alert('clase actualizado');</script>";

            header("Location:index.php");
        }
    } else {
        echo "<p>No se ha enviado el formulario.</p>";
    }
}

?>


<?php include("../../templates/header.php"); ?>
<div class="card mt-3 mb-3">
    <div class="card-header bg-secondary text-white">
        Datos de la clase
    </div>
    <div class="card-body bg-dark">
        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3" hidden="true">
                <label for="ID" class="form-label bg-dark text-white">ID</label>
                <input type="text" value="<?php echo $clase['id_clase']; ?>" class="form-control bg-dark text-white" readonly name="ID" id="ID" aria-describedby="helpId" placeholder="">
            </div>


            <div class="mb-3">
                <label for="descripcion" class="form-label bg-dark text-white">Descripcion</label>
                <input type="text" value="<?php echo $clase['descripcion']; ?>" class="form-control bg-dark text-white" name="descripcion" id="descripcion" aria-describedby="helpId" placeholder="Descripcion" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>


            <div class="mb-3">
                <label for="cant_min_participantes" class="form-label bg-dark text-white">Cant min participantes</label>
                <input type="number" value="<?php echo $clase['cant_min_participantes']; ?>" class="form-control bg-dark text-white" name="cant_min_participantes" id="cant_min_participantes" aria-describedby="helpId" placeholder="Cant min participantes">
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>

            <div class="mb-3">
                <label for="cant_max_participantes" class="form-label bg-dark text-white">Cant max participantes</label>
                <input type="number" value="<?php echo $clase['cant_max_participantes']; ?>" class="form-control bg-dark text-white" name="cant_max_participantes" id="cant_max_participantes" aria-describedby="helpId" placeholder="Cant max participantes" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>

            <div class="mb-3">
                <label for="fch_desde" class="form-label bg-dark text-white">Fecha Desde</label>
                <input type="datetime-local" value="<?php echo $clase['fch_desde']; ?>" class="form-control bg-dark text-white" name="fch_desde" id="fch_desde" aria-describedby="helpId" placeholder="Fecha Desde" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="fch_hasta" class="form-label bg-dark text-white">Fecha Hasta</label>
                <input type="datetime-local" value="<?php echo $clase['fch_hasta']; ?>" class="form-control bg-dark text-white" name="fch_hasta" id="fch_hasta" aria-describedby="helpId" placeholder="Fecha Hasta" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>





            <button type="submit" class="btn btn-success">Confirmar Cambios</button>
            <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>
<?php include("../../templates/footer.php"); ?>