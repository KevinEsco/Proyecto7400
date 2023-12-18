<?php
include("./bd.php");
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
$hoy = date("Y-m-d H:i:s");
$hoy = new DateTime($hoy);
$hoy = $hoy->format('Y-m-d 00:00:00');
$sentencia1 = $conexion->prepare("SELECT COUNT(*) as cantClases FROM clase where fch_desde >= :hoy");
$sentencia1->bindParam(":hoy", $hoy);
$sentencia1->execute();

$fila = $sentencia1->fetch(PDO::FETCH_LAZY);
$cantClasesHoy = $fila['cantClases'];

$semana = date_create('-7 day')->format('Y-m-d H:i:s');
$semana = new DateTime($semana);
$semana = $semana->format('Y-m-d 00:00:00');
$sentencia2 = $conexion->prepare("SELECT COUNT(*) as r FROM clase where fch_desde >= :semana");
$sentencia2->bindParam(":semana", $semana);
$sentencia2->execute();

$fila = $sentencia2->fetch(PDO::FETCH_LAZY);
$cantClases = $fila['r'];


$sentencia3 = $conexion->prepare("SELECT COUNT(*) as r FROM cliente");
$sentencia3->execute();

$fila = $sentencia3->fetch(PDO::FETCH_LAZY);
$cantClientes = $fila['r'];


if (isset($_SESSION['id_cliente'])) {
    $mes = date_create('-31 day')->format('Y-m-d H:i:s');
    $mes = new DateTime($mes);
    $mes = $mes->format('Y-m-d 00:00:00');
    $id_cliente = $_SESSION['id_cliente'];
    $sentencia3 = $conexion->prepare("SELECT Count(*) as r FROM inscripcion where id_cliente =:id_cliente and fecha_creacion >= :mes ");
    $sentencia3->bindParam(":id_cliente", $id_cliente);
    $sentencia3->bindParam(":mes", $mes);
    $sentencia3->execute();
    $fila = $sentencia3->fetch(PDO::FETCH_LAZY);
    $clasesGastadas = $fila['r'];

    $clasesAbono = 0;
    $sentencia4 = $conexion->prepare("SELECT ab.cant_clases as r, ab.desc_corta, cl.nombre_cliente FROM cliente as cl INNER JOIN abono as ab on ab.id_abono = cl.id_abono where cl.id_cliente =:id_cliente  ");
    $sentencia4->bindParam(":id_cliente", $id_cliente);
    $sentencia4->execute();
    $fila = $sentencia4->fetch(PDO::FETCH_LAZY);
    $clasesAbono = $fila['r'];
    $nombreAbono = $fila['desc_corta'];
    $nombreCliente = $fila['nombre_cliente'];

    $clasesRestantes = $clasesAbono - $clasesGastadas;
}




?>
<?php include("templates/header.php"); ?>
<br></br>

<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Bienvenido</h1>

        <?php if (isset($_SESSION['loggeado'])) { ?>
            <h2> Usuario <?php echo $nombreCliente ?></h2>
            <p class="col-md-8 fs-4">Clases restantes en el mes vigente : <?php echo $clasesRestantes ?></p>
            <p class="col-md-8 fs-4">Abono en el que se inscribio : <?php echo $nombreAbono ?></p>
        <?php } else {   ?>
            <p class="col-md-8 fs-4">Clases registradas ultimos 7 dias: <?php echo $cantClases ?> </p>
            <p class="col-md-8 fs-4">Clases disponibles : <?php echo $cantClasesHoy ?></p>
            <p class="col-md-8 fs-4">Cantidad de clientes registrados: <?php echo $cantClientes ?></p>
        <?php }   ?>
        </p>
    </div>
</div>
<?php include("templates/footer.php"); ?>