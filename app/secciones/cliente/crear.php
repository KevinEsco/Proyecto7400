<?php
include("../../bd.php");
$sentencia = $conexion->prepare("SELECT * FROM abono");
$sentencia->execute();
$lista_abonos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    if (!empty($_POST)) {
        // Comprobar si llegaron los campos requeridos:

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







            $nombre = (isset($_POST["nombre"]) ? $_POST["nombre"] : "");
            $codigo = (isset($_POST["codigo"]) ? $_POST["codigo"] : "");
            $email = (isset($_POST["email"]) ? $_POST["email"] : "");

            $telefono = (isset($_POST["telefono"]) ? $_POST["telefono"] : "");
            $fch_nacimiento = (isset($_POST["fch_nacimiento"]) ? $_POST["fch_nacimiento"] : "");
            $clave = (isset($_POST["clave"]) ? $_POST["clave"] : "");
            $DNI = (isset($_POST["DNI"]) ? $_POST["DNI"] : "");
            $usuario = (isset($_POST["usuario"]) ? $_POST["usuario"] :  $_POST["DNI"]);
            $id_abono = (isset($_POST["id_abono"]) ? $_POST["id_abono"] : 0);


            $sentencia = $conexion->prepare("INSERT INTO cliente(nombre_cliente,cod_cliente,email,id_abono,telefono,fch_nacimiento,clave,DNI,usuario) VALUES(:nombre,:codigo,:email,:id_abono,:telefono,:fch_nacimiento,:clave,:DNI,:usuario)");

            $sentencia->bindParam(":nombre", $nombre);
            $sentencia->bindParam(":codigo", $codigo);
            $sentencia->bindParam(":email", $email);
            $sentencia->bindParam(":id_abono", $id_abono);
            $sentencia->bindParam(":telefono", $telefono);
            $sentencia->bindParam(":fch_nacimiento", $fch_nacimiento);
            $sentencia->bindParam(":clave", $clave);
            $sentencia->bindParam(":DNI", $DNI);
            $sentencia->bindParam(":usuario", $usuario);
            $sentencia->execute();
            // // Mostrar los mensajes:
            // for ($contador = 0; $contador < count($aMensajes); $contador++)
            //     echo $aMensajes[$contador] . "<br/>";
            echo "<script language='javascript'>alert('Cliente agregado');</script>";
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
            <div class="mb-3">
                <label for="nombre" class="form-label bg-dark text-white">Nombre y apellido</label>
                <input type="text" class="form-control bg-dark text-white" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Nombre y apellido" required>

            </div>
            <div class="mb-3">
                <label for="codigo" class="form-label bg-dark text-white">Codigo</label>
                <input type="text" class="form-control bg-dark text-white" name="codigo" id="codigo" aria-describedby="helpId" placeholder="Codigo" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="email" class="form-label bg-dark text-white">Email</label>
                <input type="email" pattern="^(.+)@(.+)$" class="form-control bg-dark text-white" name="email" id="email" placeholder="email" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>

            <div class="mb-3">
                <label for="id_abono" class="form-label bg-dark text-white">Tipo abono</label>
                <select class="form-select bg-dark text-white" name="id_abono" id="id_abono">
                    <?php
                    foreach ($lista_abonos as $abono) { ?>
                        <option value="<?php echo $abono['id_abono'] ?>"><?php echo $abono['desc_corta'] ?></option>
                    <?php }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label bg-dark text-white">Telefono</label>
                <input type="number" class="form-control bg-dark text-white" name="telefono" id="telefono" aria-describedby="helpId" placeholder="telefono">
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="fch_nacimiento" class="form-label bg-dark text-white">Fecha nacimiento</label>
                <input type="date" class="form-control bg-dark text-white" name="fch_nacimiento" id="Fecha nacimiento" aria-describedby="helpId" placeholder="Fecha nacimiento">
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="DNI" class="form-label bg-dark text-white">DNI</label>
                <input type="number" class="form-control bg-dark text-white" name="DNI" id="DNI" aria-describedby="helpId" placeholder="DNI" required max="999999999">
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="clave" class="form-label bg-dark text-white">clave</label>
                <input type="password" class="form-control bg-dark text-white" name="clave" id="clave" aria-describedby="helpId" placeholder="clave" required>
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>



            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div> 
<?php include("../../templates/footer.php"); ?>