<?php
include("../../bd.php");
if (isset($_GET['ID'])) {
    # code...
    $ID = (isset($_GET['ID'])) ? $_GET['ID'] : "";
    $sentencia = $conexion->prepare("DELETE FROM clase where id_clase = :ID");
    $sentencia->bindParam(":ID", $ID);
    try {
        $sentencia->execute();
    } catch (Throwable $th) {
        if ((str_contains($th->getMessage(), "a foreign key constraint fails ")) and (str_contains($th->getMessage(), "1451"))) {

            $sentenciaNew = $conexion->prepare("SELECT *,cl.nombre_cliente FROM inscripcion ins INNER JOIN cliente cl on cl.id_cliente = ins.id_cliente WHERE id_clase = :ID");
            $sentenciaNew->bindParam(":ID", $ID);
            $sentenciaNew->execute();
            $lista_inscriptos = $sentenciaNew->fetchAll(PDO::FETCH_ASSOC);
            $inscripcionesClientes = '';
            foreach ($lista_inscriptos as $indice => $inscripcion) {

                if ($inscripcionesClientes != '') {
                    $inscripcionesClientes = $inscripcionesClientes . ", " . $inscripcion["nombre_cliente"];
                } else {
                    $inscripcionesClientes = $inscripcion["nombre_cliente"];
                }
            }
            echo "<script language='javascript'>alert('Ya hay clientes que estan inscriptos a esta clase, la clase no se puede eliminar. " . $inscripcionesClientes . "');</script>";
        }
    }
}
$sentencia = $conexion->prepare("SELECT cl.*, COUNT(ins.id_inscripcion) as inscripciones FROM `clase` as cl LEFT join inscripcion as ins on ins.id_clase = cl.id_clase GROUP BY cl.id_clase ");
$sentencia->execute();
$lista_clases = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("../../templates/header.php"); ?>
<br>


<h1>Clases</h1>





<a name="" id="" class="btn btn-primary mt-3 mb-3" href="crear.php" role="button">+ Clase</a>

<div class="table-responsive-sm">
    <table class="table table-striped table-dark table-hover table-bordered">
        <thead>
            <tr>

                <th scope="col">Descripcion </th>
                <th scope="col">Cant min participantes</th>
                <th scope="col">Cant max participantes</th>
                <th scope="col">Num.inscriptos</th>
                <th scope="col">Desde</th>
                <th scope="col">Hasta</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($lista_clases as $clase) {
                # code...
            ?>
                <tr class="">
                    <td scope="row"><?php echo $clase['descripcion']; ?></td>
                    <td><?php echo $clase['cant_min_participantes']; ?></td>
                    <td><?php echo $clase['cant_max_participantes']; ?></td>
                    <td><?php echo $clase['inscripciones']; ?></td>
                    <td><?php echo $clase['fch_desde']; ?></td>

                    <td><?php echo $clase['fch_hasta']; ?></td>
                    <td>
                        <a name="" class="btn btn-primary" href="editar.php?ID=<?php echo $clase['id_clase'] ?>role=" button"><i class="fa-solid fa-pen"></i></a>
                        <a name="" class="btn btn-danger" href="index.php?ID=<?php echo $clase['id_clase'] ?>" role="button" onclick="return confirm('Confirma eliminacion?')"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php
            }
            ?>



        </tbody>
    </table>
</div>


<?php include("../../templates/footer.php"); ?>