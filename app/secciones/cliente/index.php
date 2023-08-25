<?php
include("../../bd.php");
if (isset($_GET['ID'])) {
    # code...
    $ID = (isset($_GET['ID'])) ? $_GET['ID'] : "";
    $sentencia = $conexion->prepare("DELETE FROM cliente where id_cliente = :ID");
    $sentencia->bindParam(":ID", $ID);
    $sentencia->execute();
}
$sentencia = $conexion->prepare("SELECT * FROM cliente");
$sentencia->execute();
$lista_clientes = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("../../templates/header.php"); ?> </br>


<h1>Clientes</h1>





<a name="" id="" class="btn btn-primary mt-3 mb-3" href="crear.php" role="button">+ Cliente</a>

<div class="table-responsive-sm">
    <table class="table table-striped table-dark table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Codigo</th>
                <th scope="col">Email</th>
                <th scope="col">Telefono</th>
                <th scope="col">DNI</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($lista_clientes as $cliente) {
                # code...
            ?>
                <tr class="">
                    <td scope="row"><?php echo $cliente['nombre_cliente']; ?></td>
                    <td><?php echo $cliente['cod_cliente']; ?></td>
                    <td><?php echo $cliente['email']; ?></td>
                    <td><?php echo $cliente['telefono']; ?></td>
                    <td><?php echo $cliente['DNI']; ?></td>


                    <td>
                        <a name="" class="btn btn-primary" href="editar.php?ID=<?php echo $cliente['id_cliente'] ?>role=" button"><i class="fa-solid fa-pen"></i></a>
                        <a name="" class="btn btn-danger" href="index.php?ID=<?php echo $cliente['id_cliente'] ?>" role="button" onclick="return confirm('Confirma eliminacion?')"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>


<?php include("../../templates/footer.php"); ?>