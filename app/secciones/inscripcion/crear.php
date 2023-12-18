<?php
include("../../bd.php");
session_start();
date_default_timezone_set('America/Argentina/Buenos_Aires');


$hoy = date("Y-m-d H:i:s");
$sentencia2 = $conexion->prepare("SELECT cl.*, COUNT(ins.id_inscripcion) as inscripciones FROM `clase` as cl LEFT join inscripcion as ins on ins.id_clase = cl.id_clase WHERE cl.fch_desde > :hoy GROUP BY cl.id_clase order by cl.fch_desde ");
$sentencia2->bindParam(":hoy", $hoy);
$sentencia2->execute();
$lista_clases = $sentencia2->fetchAll(PDO::FETCH_ASSOC);



class Event
{
    public $title;
    public $start;
    public $end;
    public $color;
    // public $id;
    public $textColor;

    // public function __construct($nombre, $precio) {
    //     $this->nombre = $nombre;
    //     $this->precio = $precio;
    // }
}

$lista_eventos = array();
foreach ($lista_clases as $indice => $clase) {
    $newEvent = new Event();
    $inscripcionesRestantes = $clase["cant_max_participantes"] - $clase["inscripciones"];

    if ($inscripcionesRestantes <= 0) {
        $newEvent->color = "Red";
    } elseif ($inscripcionesRestantes <= 5) {
        $newEvent->color = "Yellow";
        $newEvent->textColor = "Black";
    } else {
        $newEvent->color = "Green";
    }
    $newEvent->title = $clase["descripcion"] . "-Disponibles: " . $inscripcionesRestantes . "/" . $clase["cant_max_participantes"];
    // Crear un objeto DateTime desde la cadena original
    $fechaObjeto = DateTime::createFromFormat('Y-m-d H:i:s', $clase["fch_desde"]);

    // Obtener la fecha en el formato deseado
    $fechaFormateada = $fechaObjeto->format('Y-m-d\TH:i');
    $newEvent->start = $fechaFormateada;

    $fechaObjeto = DateTime::createFromFormat('Y-m-d H:i:s', $clase["fch_hasta"]);

    // Obtener la fecha en el formato deseado
    $fechaFormateada = $fechaObjeto->format('Y-m-d\TH:i');
    $newEvent->end = $fechaFormateada;

    // events: [{
    //     title: 'Clase nocturna',
    //     start: '2023-18-12T01:30',
    //     allDay: false
    // }],
    array_push($lista_eventos, $newEvent);
}
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
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: "es",
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },

            events: <?php echo json_encode($lista_eventos) ?>,

        });
        calendar.render();
    });
</script>

<div class="card mt-3 mb-3">
    <div class="card-header bg-secondary text-white">
        Nueva Inscripcion
    </div>
    <div class="card-body bg-dark">

        <form action="" method="post" enctype="multipart/form-data">


            <div class="container-calendar" style="background-color:white">
                <div class="col-md-8 offset-md-2">
                    <div id='calendar'></div>
                </div>
            </div>

            </body>
            <div class="mb-3">
                <label for="id_clase" class="form-label bg-dark text-white">Seleccione la clase a anotarse</label>
                <select class="form-select bg-dark text-white" name="id_clase" id="id_clase">
                    <?php foreach ($lista_clases as $clase) { ?>
                        <option value="<?php echo $clase['id_clase'] ?>"><?php echo  $clase['descripcion'] . " " . "(" . $clase['fch_desde'] . " a " . $clase['fch_hasta'] . ")" ?> </option>
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