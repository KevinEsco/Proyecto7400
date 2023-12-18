<?php
include("../../bd.php");
$sentencia = $conexion->prepare("SELECT * FROM abono");
$sentencia->execute();
$lista_abonos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    if (!empty($_POST)) {
        // Comprobar si llegaron los campos requeridos:

        $nombre = (isset($_POST["nombre"]) ? $_POST["nombre"] : "");
        $codigo = (isset($_POST["codigo"]) ? $_POST["codigo"] : "");
        $email = (isset($_POST["email"]) ? $_POST["email"] : "");

        $telefono = (isset($_POST["telefono"]) ? $_POST["telefono"] : "");
        $fch_nacimiento = (isset($_POST["fch_nacimiento"]) ? $_POST["fch_nacimiento"] : "");
        $clave = (isset($_POST["clave"]) ? $_POST["clave"] : "");
        $DNI = (isset($_POST["DNI"]) ? $_POST["DNI"] : "");
        $usuario = (isset($_POST["usuario"]) ? $_POST["usuario"] :  $_POST["DNI"]);
        $id_abono = (isset($_POST["id_abono"]) ? $_POST["id_abono"] : 0);

        //agregar validacion si esta correcto continuar y ejecutar el insert
        $errorNombre = $errorCodigo = $errorEmail = $errorTelefono = $errorFch_nacimiento = $errorClave = $errorDNI = $errorUsuario = $errorId_abono = false;


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
        function checkTelefono($telefono)
        {
            if (!preg_match("/^['a-zA-Z-' ]*$/", $telefono)) {
                return true;
            }
        }
        function checkFch_nacimiento($fch_nacimiento)
        {
            if (!preg_match("/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/", $fch_nacimiento)) {
                return true;
            }
            $currentDate = new DateTime();
            $fchIngresada = new DateTime($fch_nacimiento);


            if ($fchIngresada > $currentDate) {
                return true;
            }
            $fchMinima = clone $currentDate;
            // $edadMinima->modify("-{7} years");
            $fchMinima = date('d-m-Y', strtotime('-7 year'));
            if ($fchIngresada > $fchMinima) {
                return true;
            }
        }
        function checkClave($clave)
        {
            if (strlen($clave) == 0 or strlen($clave) < 4) {
                return true;
            }
        }
        function checkDNI($DNI)
        {
            if (!preg_match("/^\d{1,2}\.?\d{3}\.?\d{3}$/", $DNI)) {
                return true;
            }
        }
        $errorNombre = checkNombre($nombre);
        if (strlen($fch_nacimiento) > 1) {
            $errorFch_nacimiento = checkFch_nacimiento($fch_nacimiento);
        }
        if ($errorNombre) {
            $mensaje = "<p>El campo nombre no puede contener numeros ni caracteres especiales. Se ingreso " . $nombre . "</p>";
            echo $mensaje;
            // echo "<script language='javascript'>alert('El campo nombre no puede contener numeros ni caracteres especiales. Se ingreso:" . $nombre . "');</script>";
        }
        if ($errorFch_nacimiento) {
            $mensaje = "<p>El campo fecha de nacimiento es invalido. La edad minima es de 7 anios. Se ingreso: " . $fch_nacimiento . "</p>";
            echo $mensaje;
            // echo "<script language='javascript'>alert('El campo nombre no puede contener numeros ni caracteres especiales. Se ingreso:" . $nombre . "');</script>";
        }


        if (!$errorNombre and !$errorCodigo and !$errorEmail and !$errorTelefono and !$errorFch_nacimiento and !$errorClave and !$errorDNI) {
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
            try {
                $sentencia->execute();
            } catch (Throwable $th) {
                if ((str_contains($th->getMessage(), " for key 'DNI'")) and (str_contains($th->getMessage(), "1062"))) {
                    echo "<script language='javascript'>alert('Ya hay un cliente registrado con el mismo DNI');</script>";
                }
            }
        }

        // echo "<script language='javascript'>alert('Cliente agregado');</script>";
        // header("Location:index.php");



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
                <input type="text" class="form-control bg-dark text-white" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Nombre y apellido" required maxlength="200">

            </div>
            <div class="mb-3">
                <label for="codigo" class="form-label bg-dark text-white">Codigo</label>
                <input type="text" class="form-control bg-dark text-white" name="codigo" id="codigo" aria-describedby="helpId" placeholder="Codigo" required maxlength="10">

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
            </div>
            <div class="mb-3">
                <label for="fch_nacimiento" class="form-label bg-dark text-white">Fecha nacimiento</label>
                <input type="date" class="form-control bg-dark text-white" name="fch_nacimiento" id="Fecha nacimiento" aria-describedby="helpId" placeholder="Fecha nacimiento" value="01-01-2000">
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="DNI" class="form-label bg-dark text-white">DNI</label>
                <input type="number" class="form-control bg-dark text-white" name="DNI" id="DNI" aria-describedby="helpId" placeholder="DNI" required min="1000000" max="999999999">
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>
            <div class="mb-3">
                <label for="clave" class="form-label bg-dark text-white">clave</label>
                <input type="password" class="form-control bg-dark text-white" name="clave" id="clave" aria-describedby="helpId" placeholder="clave" required minlength="4">
                <!-- <small id="helpId" class="form-text text-muted">Help text</small> -->
            </div>



            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>
<?php include("../../templates/footer.php"); ?>