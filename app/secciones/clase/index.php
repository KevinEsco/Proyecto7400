<?php
include("../../bd.php");
if (isset($_GET['ID'])) {
    # code...
    $ID = (isset($_GET['ID'])) ? $_GET['ID'] : "";
    $sentencia = $conexion->prepare("DELETE FROM clase where id_clase = :ID");
    $sentencia->bindParam(":ID", $ID);
    $sentencia->execute();
}
$sentencia = $conexion->prepare("SELECT * FROM clase");
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