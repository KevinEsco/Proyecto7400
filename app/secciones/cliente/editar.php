<?php
include("../../bd.php");
if (isset($_GET['ID'])) {
    # code...
    $ID = (isset($_GET['ID'])) ? $_GET['ID'] : "";
    $sentencia = $conexion->prepare("SELECT * FROM cliente where id_cliente = :ID");
    $sentencia->bindParam(":ID", $ID);
    $sentencia->execute();

    $cliente = $sentencia->fetch(PDO::FETCH_LAZY);

    $sentencia2 = $conexion->prepare("SELECT * FROM abono");
    $sentencia2->execute();
    $lista_abonos = $sentencia2->fetchAll(PDO::FETCH_ASSOC);
}

if ($_POST) {
    if (!empty($_POST)) {
        // Comprobar si llegaron los campos requeridos:
        if (isset($_POST['cod_cliente']) && isset($_POST['desc_corta']) && isset($_POST['desc_larga']) && isset($_POST['cant_clientes'])) {
            // cod_cliente:
            if (empty($_POST['cod_cliente']))
                $aErrores[] = "Debe especificar el cod_cliente";
            else {
                // Comprobar mediante una expresión regular, que sólo contiene letras y espacios:
                // if (preg_match($patron_texto, $_POST['txtNombre']))
                //     $aMensajes[] = "Nombre: [" . $_POST['txtNombre'] . "]";
                // else
                //     $aErrores[] = "El cod_cliente sólo puede contener letras y espacios";
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

            // cant_clientes:

            if (empty($_POST['cant_clientes']))
                $aErrores[] = "Debe especificar cant_clientes";
            else {
                if ((isset($_POST['cant_clientes'])) && (!empty($_POST['cant_clientes']))) {
                    if (is_numeric($_POST['cant_clientes']))
                        $aMensajes[] = "Edad: [" . $_POST['cant_clientes'] . "]";
                    else
                        $aErrores[] = "El campo cant_clientes debe contener un número.";
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
            $nombre_cliente = (isset($_POST["nombre_cliente"]) ? $_POST["nombre_cliente"] : "");
            $codigo = (isset($_POST["cod_cliente"]) ? $_POST["cod_cliente"] : "");
            $email = (isset($_POST["email"]) ? $_POST["email"] : "");

            $telefono = (isset($_POST["telefono"]) ? $_POST["telefono"] : "");
            $fch_nacimiento = (isset($_POST["fch_nacimiento"]) ? $_POST["fch_nacimiento"] : "");
            $clave = (isset($_POST["clave"]) ? $_POST["clave"] : "");
            $DNI = (isset($_POST["DNI"]) ? $_POST["DNI"] : "");
            $usuario = (isset($_POST["usuario"]) ? $_POST["usuario"] :  $_POST["DNI"]);
            $id_abono = (isset($_POST["id_abono"]) ? $_POST["id_abono"] : 0);




            $sentencia = $conexion->prepare("UPDATE cliente SET nombre_cliente =:nombre_cliente,cod_cliente=:codigo,email=:email,id_abono=:id_abono,telefono=:telefono,fch_nacimiento=:fch_nacimiento,clave=:clave,DNI=:DNI,usuario=:usuario WHERE id_cliente = :ID");


            $sentencia->bindParam(":ID", $ID);
            $sentencia->bindParam(":nombre_cliente", $nombre_cliente);
            $sentencia->bindParam(":codigo", $codigo);
            $sentencia->bindParam(":email", $email);
            $sentencia->bindParam(":id_abono", $id_abono);
            $sentencia->bindParam(":telefono", $telefono);
            $sentencia->bindParam(":fch_nacimiento", $fch_nacimiento);
            $sentencia->bindParam(":clave", $clave);
            $sentencia->bindParam(":DNI", $DNI);
            $sentencia->bindParam(":usuario", $usuario);
            // // Mostrar los mensajes:
            $sentencia->execute();
            // for ($contador = 0; $contador < count($aMensajes); $contador++)
            // //     echo $aMensajes[$contador] . "<br/>";
            // echo "<script language='javascript'>alert('cliente actualizado');</script>";

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
        Datos del cliente
    </div>
    <div class="card-body bg-dark">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3" hidden="true">
                <label for="ID" class="form-label bg-dark text-white">ID</label>
                <input type="text" value="<?php echo $cliente['id_cliente']; ?>" class="form-control bg-dark text-white" readonly name="ID" id="ID" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="nombre_cliente" class="form-label bg-dark text-white">Nombre y apellido</label>
                <input type="text" value="<?php echo $cliente['nombre_cliente']; ?>" class="form-control bg-dark text-white" name="nombre_cliente" id="nombre_cliente" aria-describedby="helpId" placeholder="Nombre y apellido" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="cod_cliente" class="form-label bg-dark text-white">Codigo</label>
                <input type="text" value="<?php echo $cliente['cod_cliente']; ?>" class="form-control bg-dark text-white" name="cod_cliente" id="cod_cliente" aria-describedby="helpId" placeholder="Codigo" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="email" class="form-label bg-dark text-white">Email</label>
                <input type="text" value="<?php echo $cliente['email']; ?>" class="form-control bg-dark text-white" name="email" id="email" aria-describedby="helpId" placeholder="Email" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="id_abono" class="form-label bg-dark text-white">Tipo abono</label>
                <select class="form-select bg-dark text-white" name="id_abono" id="id_abono">
                    <?php foreach ($lista_abonos as $abono) { ?>
                        <option <?php echo ($cliente['id_abono'] == $abono['id_abono']) ? "selected" : ""; ?> value="<?php echo $abono['id_abono'] ?>"><?php echo $abono['desc_corta'] ?> </option>
                    <?php }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="telefono" class="form-label bg-dark text-white">Telefono</label>
                <input type="number" value="<?php echo $cliente['telefono']; ?>" class="form-control bg-dark text-white" name="telefono" id="telefono" aria-describedby="helpId" placeholder="telefono">
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="fch_nacimiento" class="form-label bg-dark text-white">Fecha nacimiento</label>
                <input type="date" value="<?php echo $cliente['fch_nacimiento']; ?>" class="form-control bg-dark text-white" name="fch_nacimiento" id="fch_nacimiento" aria-describedby="helpId" placeholder="Fecha nacimiento">
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="DNI" class="form-label bg-dark text-white">DNI</label>
                <input type="number" value="<?php echo $cliente['DNI']; ?>" class="form-control bg-dark text-white" name="DNI" id="DNI" aria-describedby="helpId" placeholder="DNI" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="clave" class="form-label bg-dark text-white">clave</label>
                <input type="password" value="<?php echo $cliente['clave']; ?>" class="form-control bg-dark text-white" name="clave" id="clave" aria-describedby="helpId" placeholder="clave" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>



            <button type="submit" class="btn btn-success">Confirmar cambios</button>
            <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>
<?php include("../../templates/footer.php"); ?>