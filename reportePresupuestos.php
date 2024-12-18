<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
//
include_once 'procesos/Servicios.php';
$Servicios = new Servicios();
include_once 'procesos/Clientes.php';
$Clientes = new Clientes();
include_once 'procesos/Usuarios.php';
$Usuarios = new Usuarios();
include_once 'procesos/Vehiculos.php';
$Vehiculos = new Vehiculos();
include_once 'procesos/Presupuestos.php';
$Presupuestos = new Presupuestos();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // echo "Parametros: " . implode(" - ", $_POST);
    if ((isset($_POST["_f"]) && $_POST["_f"] >= 1) && (isset($_POST["isd"]) && $_POST["isd"] >= 0)) {
        $result = $Presupuestos->getPresupuestoPorFiltro($_POST["isd"], $_POST["ish"], $_POST["ted"], $_POST["teh"], $_POST["mod"], $_POST["moh"], $_POST["rd"], $_POST["rh"], $_POST["ed"], $_POST["eh"], $_POST["ced"], $_POST["ceh"]);
    } else {
        $result = $Presupuestos->getPresupuestos();
    }
};
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["id_presupuesto"]) && $_GET["id_presupuesto"] >= 0) {
        $result = $Presupuestos->getPresupuesto($_GET["id_presupuesto"]);
    } else {
        $result = $Presupuestos->getPresupuestos();
    }
};
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Presupuestos x Servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    .container-index {
        background: url(./assets/img/imagen_sin_fondo.png) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        height: 100vh;
    }
</style>

<body class="container-index">
    <?php
    include_once("navbar.php")
    ?>
    <div class="container-xxl container-fluid bg-white mt-2">
        <h2>Reporte de Presupuestos x Servicios</h2>
        <form action="reportePresupuestos.php" method="POST" class="row g-2 align-items-center">
            <div class="col-sm col-md">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" id="Servicio">Servicio</span>
                    <select class="form-select form-select-sm" id="isd" name="isd" value="0" aria-label="Servicio">
                        <option value="0" selected>Desde</option>
                        <?php
                        $result_ = $Servicios->getServicios();
                        while ($row = $result_->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['id_servicio']; ?>"><?php echo $row['id_servicio']; ?> - <?php echo $row['descripcion']; ?></option>
                        <?php } ?>
                    </select>

                    <select class="form-select form-select-sm" id="ish" name="ish" value="999999999" aria-label="Servicio">
                        <option value="999999999" selected>Hasta</option>
                        <?php
                        $result_ = $Servicios->getServicios();
                        while ($row = $result_->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['id_servicio']; ?>"><?php echo $row['id_servicio']; ?> - <?php echo $row['descripcion']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-sm col-md">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" id="Total Estimado">Total Estimado</span>
                    <input class="form-control" type="number" name="ted" id="ted" value="0" aria-label="Total Estimado Desde">

                    <input class="form-control" type="number" name="teh" id="teh" value="999999999999" aria-label="Total Estimado Hasta">
                </div>
            </div>
            <div class="col-sm col-md">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" id="Mano de Obra">Mano de Obra</span>
                    <input class="form-control" type="number" name="mod" id="mod" value="0" aria-label="Mano de Obra Desde">

                    <input class="form-control" type="number" name="moh" id="moh" value="999999999999" aria-label="Mano de Obra Hasta">
                </div>
            </div>
            <div class="col-sm col-md">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" id="Total Repuestos">Total Repuestos</span>
                    <input class="form-control" type="number" name="rd" id="rd" value="0" aria-label="Total Repuestos Desde">

                    <input class="form-control" type="number" name="rh" id="rh" value="999999999999" aria-label="Total Repuestos Hasta">
                </div>
            </div>
            <div class="col-sm col-md">
                <!-- 'PENDIENTE', 'APROBADO', 'RECHAZADO' -->
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" id="Estado">Estado</span>
                    <select class="form-select form-select-sm" id="ed" name="ed" value="AA" aria-label="Estado">
                        <option value="AAAAAAAAAA" selected>Desde</option>
                        <option value="PENDIENTE">PENDIENTE</option>
                        <option value="APROBADO">APROBADO</option>
                        <option value="RECHAZADO">RECHAZADO</option>
                    </select>

                    <select class="form-select form-select-sm" id="eh" name="eh" value="ZZ" aria-label="Estado">
                        <option value="ZZZZZZZZZZ" selected>Hasta</option>
                        <option value="PENDIENTE">PENDIENTE</option>
                        <option value="APROBADO">APROBADO</option>
                        <option value="RECHAZADO">RECHAZADO</option>
                    </select>
                </div>
            </div>
            <div class="col-sm col-md">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" id="Fecha">Fecha</span>
                    <input type="date" class="form-control form-control-sm" id="ced" name="ced">

                    <input type="date" class="form-control form-control-sm" id="ceh" name="ceh">
                </div>
            </div>
            <div class="col-sm-1 col-md-1">
                <input type="hidden" name="_f" id="_f" value="1">
                <button type="submit" class="btn btn-sm btn-outline-primary">Filtrar</button>
            </div>
        </form>
        <!-- Tabla de Presupuestos -->
        <table class="table table-striped table-responsive table-bordered mt-2">
            <thead class="table-primary">
                <tr>
                    <th class="text-center"><strong>Descripcion</strong></th>
                    <th class="text-center"><strong>Servicio</strong></th>
                    <th class="text-center"><strong>Cliente</strong></th>
                    <th class="text-center"><strong>Vehiculo</strong></th>
                    <th class="text-center"><strong>Mecanico</strong></th>
                    <th class="text-center"><strong>Total Estimado</strong></th>
                    <th class="text-center"><strong>Total Mano Obra</strong></th>
                    <th class="text-center"><strong>Total Repuestos</strong></th>
                    <th class="text-center"><strong>Estado</strong></th>
                    <th class="text-center"><strong>Fecha de Alta</strong></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($presupuesto = $result->fetch_assoc()):
                    $result_s = $Servicios->getServicio($presupuesto['id_servicio']);
                    if ($result_s->num_rows > 0) {
                        $row_s = $result_s->fetch_assoc();
                        $id_servicio = $row_s['id_servicio'];
                        $id_vehiculo = $row_s['id_vehiculo'];
                        $id_cliente = $row_s['id_cliente'];
                        $id_usuario = $row_s['id_usuario'];
                        $descripcion = $row_s['descripcion'];
                    }
                ?>
                    <tr>
                        <td><?php echo $presupuesto['descripcion']; ?></td>
                        <td><?php echo "$id_servicio - $descripcion"; ?></td>
                        <td>
                            <?php
                            $result_ = $Clientes->getCliente($id_cliente);
                            if ($result_->num_rows > 0) {
                                $row = $result_->fetch_assoc();
                                $cedula = $row['cedula'];
                                $nombre_apellido = $row['nombre_apellido'];
                                echo "$cedula - $nombre_apellido";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $result_ = $Vehiculos->getVehiculo($id_vehiculo);
                            if ($result_->num_rows > 0) {
                                $row = $result_->fetch_assoc();
                                $marca = $row['marca'];
                                $modelo = $row['modelo'];
                                $placa = $row['placa'];
                                echo "$marca - $modelo - chapa: $placa";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $result_ = $Usuarios->getUsuario($id_usuario);
                            if ($result_->num_rows > 0) {
                                $row = $result_->fetch_assoc();
                                $nombre = $row['nombre'];
                                $apellido = $row['apellido'];
                                echo "$nombre $apellido";
                            }
                            ?>
                        </td>
                        <td class="text-end"><?php echo number_format($presupuesto['total_estimado'], 0, '', '.'); ?></td>
                        <td class="text-end"><?php echo number_format($presupuesto['mano_obra'], 0, '', '.'); ?></td>
                        <td class="text-end"><?php echo number_format($presupuesto['repuestos'], 0, '', '.'); ?></td>
                        <td class="text-center"><?php echo $presupuesto['estado']; ?></td>
                        <td class="text-center">
                            <?php
                            $timestamp = strtotime($presupuesto['creado_en']);
                            echo date('d/m/Y', $timestamp);
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<script>
    // JavaScript para establecer valores por defecto
    document.addEventListener("DOMContentLoaded", function() {
        // Obtener los elementos de fecha
        const fechaDesde = document.getElementById('ced');
        const fechaHasta = document.getElementById('ceh');

        // Crear la fecha de hoy
        const hoy = new Date();
        const hoyISO = hoy.toISOString().split('T')[0];

        // Calcular 30 días atrás
        const fechaAtras = new Date();
        fechaAtras.setDate(hoy.getDate() - 30);
        const fechaAtrasISO = fechaAtras.toISOString().split('T')[0];

        // Asignar valores por defecto
        fechaDesde.value = fechaAtrasISO;
        fechaHasta.value = hoyISO;
    });
</script>