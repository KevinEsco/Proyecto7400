<?php
include("../../bd.php");
session_start();
date_default_timezone_set('America/Argentina/Buenos_Aires');


$hoy = date("Y-m-d H:i:s");
$sentencia2 = $conexion->prepare("SELECT * FROM clase where fch_desde > :hoy ");
$sentencia2->bindParam(":hoy", $hoy);
$sentencia2->execute();
$lista_clases = $sentencia2->fetchAll(PDO::FETCH_ASSOC);


if ($_POST) {
    if (!empty($_POST)) {
        $mes = date_create('-31 day')->format('Y-m-d H:i:s');
        $mes = new DateTime($mes);
        $mes = $mes->format('Y-m-d 00:00:00');
        $id_cliente = $_SESSION['id_cliente'];
        $id_clase = (isset($_POST["id_clase"]) ? $_POST["id_clase"] : "");
        $sentencia3 = $conexion->prepare("SELECT Count(*) as r FROM inscripcion where id_cliente =:id_cliente and fecha_creacion >= :mes ");
        $sentencia3->bindParam(":id_cliente", $id_cliente);
        $sentencia3->bindParam(":mes", $mes);
        $sentencia3->execute();
        $fila = $sentencia3->fetch(PDO::FETCH_LAZY);
        $clasesGastadas = $fila['r'];

        $clasesAbono = 0;
        $sentencia4 = $conexion->prepare("SELECT ab.cant_clases as r, cla.cant_max_participantes as max FROM cliente as cl INNER JOIN abono as ab on ab.id_abono = cl.id_abono INNER JOIN clase as cla on cla.id_clase =:id_clase where cl.id_cliente =:id_cliente  ");
        $sentencia4->bindParam(":id_cliente", $id_cliente);
        $sentencia4->bindParam(":id_clase", $id_clase);

        $sentencia4->execute();
        $fila = $sentencia4->fetch(PDO::FETCH_LAZY);
        $clasesAbono = $fila['r'];
        $cantMax = $fila['max'];

        $cantInscriptos = 0;
        $sentencia5 = $conexion->prepare("SELECT Count(*) as r FROM inscripcion WHERE id_clase =:id_clase");
        $sentencia5->bindParam(":id_clase", $id_clase);

        $sentencia5->execute();
        $fila = $sentencia5->fetch(PDO::FETCH_LAZY);
        $cantInscriptos = $fila['r'];
        try {
            if ($clasesGastadas >= $clasesAbono) {
                throw new Exception("Error Processing Request", 5);
            }
            if ($cantInscriptos >= $cantMax) {
                throw new Exception("Error inscriptos", 7);
            }


            $cliente_asiste = (isset($_POST["cliente_asiste"]) ? $_POST["cliente_asiste"] : "S");

            $sentencia = $conexion->prepare("INSERT INTO inscripcion(id_cliente,id_clase,cliente_asiste, fecha_creacion) VALUES(:id_cliente,:id_clase,:cliente_asiste, :hoy)");

            $sentencia->bindParam(":id_cliente", $id_cliente);
            $sentencia->bindParam(":id_clase", $id_clase);
            $sentencia->bindParam(":cliente_asiste", $cliente_asiste);
            $sentencia->bindParam(":hoy", $hoy);

            $sentencia->execute();
            echo "<script language='javascript'>alert('Se agrego la inscripcion correctamente');</script>";
        } catch (\Throwable $th) {
            if ($th->getCode() == 23000) {

                echo "<script language='javascript'>alert('Ya se encuentra anotado a esta clase');</script>";
            }
            if ($th->getCode() == 5) {
                echo "<script language='javascript'>alert('Ha gastado todas las clases mensuales disponibles');</script>";
            }
            if ($th->getCode() == 7) {
                echo "<script language='javascript'>alert('El cupo de inscripciones para esta clase esta lleno');</script>";
            }
        }
    } else {
        echo "<p>No se ha enviado el formulario.</p>";
    }
}
?>

<?php include("../../templates/header.php"); ?>
<div class="card mt-3 mb-3">
    <div class="card-header bg-secondary text-white">
        Datos del Abono
    </div>
    <div class="card-body bg-dark">
        <form action="" method="post" enctype="multipart/form-data">




            <div class="mb-3">
                <label for="id_clase" class="form-label bg-dark text-white">Tipo abono</label>
                <select class="form-select bg-dark text-white" name="id_clase" id="id_clase">
                    <?php foreach ($lista_clases as $clase) { ?>
                        <option value="<?php echo $clase['id_clase'] ?>"><?php echo $clase['descripcion'] ?> </option>
                    <?php }
                    ?>
                </select>
            </div>



            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>
    </div>

</div>
<?php include("../../templates/footer.php"); ?>
<?php include("../../templates/footer.php"); ?>