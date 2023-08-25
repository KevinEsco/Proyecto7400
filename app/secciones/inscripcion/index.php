<?php
include("../../bd.php");
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_SESSION['id_cliente'])) {
    $id_cliente = $_SESSION['id_cliente'];
    $sentencia = $conexion->prepare("SELECT *, cl.descripcion, cl.fch_desde, cl.fch_hasta FROM inscripcion as i INNER JOIN clase as cl on cl.id_clase = i.id_clase where id_cliente = :id_cliente");
    $sentencia->bindParam(":id_cliente", $id_cliente);
    $sentencia->execute();
    $lista_inscripciones = $sentencia->fetchAll(PDO::FETCH_ASSOC);
}
if (isset($_GET['ID'])) {
    # code...
    $ID = (isset($_GET['ID'])) ? $_GET['ID'] : "";
    $sentencia = $conexion->prepare("DELETE FROM inscripcion where id_inscripcion = :ID");
    $sentencia->bindParam(":ID", $ID);
    $sentencia->execute();
    header("Location:./index.php");
}
?>

<?php include("../../templates/header.php"); ?> </br>


<h1>Inscripcion</h1>





<a name="" id="" class="btn btn-primary mt-3 mb-3" href="crear.php" role="button">+ Inscripcion</a>

<div class="table-responsive-sm">
    <table class="table table-striped table-dark table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col">Clase</th>
                <th scope="col">Fecha Creacion</th>
                <th scope="col">Hora incio clase</th>
                <th scope="col">Hora fin clase</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($lista_inscripciones as $inscripcion) {
                # code...
            ?>
                <tr class="">
                    <td scope="row"><?php echo $inscripcion['descripcion']; ?></td>
                    <td><?php echo $inscripcion['fecha_creacion']; ?></td>
                    <td><?php echo $inscripcion['fch_desde']; ?></td>
                    <td><?php echo $inscripcion['fch_hasta']; ?></td>



                    <td>

                        <a name="" class="btn btn-danger" href="index.php?ID=<?php echo $inscripcion['id_inscripcion'] ?>" role="button" onclick="return confirm('Confirma elimiacion?')" onclick="return confirm('Confirma eliminacion?')"><i class="fa-solid fa-trash"></i></a>

                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<?php include("../../templates/footer.php"); ?>