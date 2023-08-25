<?php
include("../../bd.php");
if (isset($_GET['ID'])) {
    # code...
    $ID = (isset($_GET['ID'])) ? $_GET['ID'] : "";
    $sentencia = $conexion->prepare("SELECT * FROM abono where id_abono = :ID");
    $sentencia->bindParam(":ID", $ID);
    $sentencia->execute();

    $abono = $sentencia->fetch(PDO::FETCH_LAZY);
}

if ($_POST) {
    if (!empty($_POST)) {
        // Comprobar si llegaron los campos requeridos:
        if (isset($_POST['cod_abono']) && isset($_POST['desc_corta']) && isset($_POST['desc_larga']) && isset($_POST['cant_clases'])) {
            // cod_abono:
            if (empty($_POST['cod_abono']))
                $aErrores[] = "Debe especificar el cod_abono";
            else {
                // Comprobar mediante una expresión regular, que sólo contiene letras y espacios:
                // if (preg_match($patron_texto, $_POST['txtNombre']))
                //     $aMensajes[] = "Nombre: [" . $_POST['txtNombre'] . "]";
                // else
                //     $aErrores[] = "El cod_abono sólo puede contener letras y espacios";
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


            $desc_corta = (isset($_POST["desc_corta"]) ? $_POST["desc_corta"] : "");
            $cod_abono = (isset($_POST["cod_abono"]) ? $_POST["cod_abono"] : "");
            $desc_larga = (isset($_POST["desc_larga"]) ? $_POST["desc_larga"] : "");
            $cant_clases = (isset($_POST["cant_clases"]) ? $_POST["cant_clases"] : "");
            $sentencia = $conexion->prepare("UPDATE abono SET desc_corta =:desc_corta,cod_abono=:cod_abono,desc_larga=:desc_larga,cant_clases=:cant_clases WHERE id_abono = :ID");

            $sentencia->bindParam(":desc_corta", $desc_corta);
            $sentencia->bindParam(":cod_abono", $cod_abono);
            $sentencia->bindParam(":desc_larga", $desc_larga);
            $sentencia->bindParam(":cant_clases", $cant_clases);
            $sentencia->bindParam(":ID", $ID);
            $sentencia->execute();
            // // Mostrar los mensajes:
            // for ($contador = 0; $contador < count($aMensajes); $contador++)
            // //     echo $aMensajes[$contador] . "<br/>";
            // echo "<script language='javascript'>alert('Abono actualizado');</script>";

            header("Location:index.php");
        }
    } else {
        echo "<p>No se ha enviado el formulario.</p>";
    }
}

?>

<?php include("../../templates/header.php");


?>

<div class="card">
    <div class="card-header bg-secondary text-white">
        Datos del Abono
    </div>
    <div class="card-body bg-dark">
        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3" hidden="true">
                <label for="ID" class="form-label bg-dark text-white">ID</label>
                <input type="text" value="<?php echo $abono['id_abono']; ?>" class="form-control bg-dark text-white" readonly name="ID" id="ID" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="cod_abono" class="form-label bg-dark text-white">Codigo</label>
                <input type="text" value="<?php echo $abono['cod_abono']; ?>" class="form-control bg-dark text-white" name="cod_abono" id="cod_abono" aria-describedby="helpId" placeholder="Codigo" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>


            <div class="mb-3">
                <label for="desc_corta" class="form-label bg-dark text-white">Descripcion Corta</label>
                <input type="text" value="<?php echo $abono['desc_corta']; ?>" class="form-control bg-dark text-white" name="desc_corta" id="desc_corta" aria-describedby="helpId" placeholder="Descripcion Corta" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>

            <div class="mb-3">
                <label for="desc_larga" class="form-label bg-dark text-white">Descripcion Larga</label>
                <input type="text" value="<?php echo $abono['desc_larga']; ?>" class="form-control bg-dark text-white" name="desc_larga" id="desc_larga" aria-describedby="helpId" placeholder="Descripcion Larga">
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>



            <div class="mb-3">
                <label for="cant_clases" class="form-label bg-dark text-white">Cant clases mensuales</label>
                <input type="number" value="<?php echo $abono['cant_clases']; ?>" class="form-control bg-dark text-white" name="cant_clases" id="cant_clases" aria-describedby="helpId" placeholder="Cantidad de clases" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>

            <button type="submit" class="btn btn-success">Confirmar cambios</button>
            <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>