<?php
include("../../bd.php");
if (isset($_GET['ID'])) {
    # code...
    $ID = (isset($_GET['ID'])) ? $_GET['ID'] : "";
    $sentencia = $conexion->prepare("DELETE FROM abono where id_abono = :ID");
    $sentencia->bindParam(":ID", $ID);
    try {
        $sentencia->execute();
    } catch (\Throwable $th) {
        if ($th->getCode() == 23000) {
            echo "<script language='javascript'>alert('No se puede eliminar el abono porque se encuentra asociado a otros clientes.');</script>";
        }
    }
}
$sentencia = $conexion->prepare("SELECT * FROM abono");
$sentencia->execute();
$lista_abonos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("../../templates/header.php"); ?> </br>


<h1>Abonos</h1>




<a name="" id="" class="btn btn-primary mt-3 mb-3" href="crear.php" role="button">+ Abono</a>
<div class="table-responsive-sm">
    <table class="table table-striped table-dark table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col">Codigo</th>
                <th scope="col">Descripcion corta</th>
                <th scope="col">Descripcion larga</th>
                <th scope="col">Cantidad de clases</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($lista_abonos as $abono) {
            ?>
                <tr class="">
                    <td scope="row"><?php echo $abono['cod_abono']; ?></td>
                    <td><?php echo $abono['desc_corta']; ?></td>
                    <td><?php echo $abono['desc_larga']; ?></td>
                    <td><?php echo $abono['cant_clases']; ?></td>
                    <td>

                        <a name="" class="btn btn-primary m-2" href="editar.php?ID=<?php echo $abono['id_abono'] ?>" role="button"><i class="fa-solid fa-pen"></i></a>
                        <a name="" class="btn btn-danger m-2" href="index.php?ID=<?php echo $abono['id_abono'] ?>" role="button" onclick="return confirm('Confirma eliminacion?')"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

</div>
<?php include("../../templates/footer.php"); ?>