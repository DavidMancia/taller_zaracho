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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // echo "Parametros: " . implode("", $_POST);
    if ((isset($_POST["_f"]) && $_POST["_f"] >= 1) && (isset($_POST["vd"]) && $_POST["vd"] >= 0)) {
        $result = $Servicios->getServicioPorFiltro($_POST["vd"], $_POST["vh"], $_POST["cd"], $_POST["ch"], $_POST["ud"], $_POST["uh"], $_POST["ed"], $_POST["eh"], $_POST["ced"], $_POST["ceh"]);
    } else {
        $result = $Servicios->getServicios();
    }
};
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["id_servicio"]) && $_GET["id_servicio"] >= 0) {
        $result = $Servicios->getServicio($_GET["id_servicio"]);
    } else {
        $result = $Servicios->getServicios();
    }
};
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Servicios</title>
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
        <h2>Reporte de Servicios</h2>
        <form action="reportes.php" method="POST" class="row g-2 align-items-center">
            <div class="col-sm col-md">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" id="Vehiculos">Vehiculo</span>
                    <select class="form-select form-select-sm" id="vd" name="vd" value="0" aria-label="Vehiculos">
                        <option value="0" selected>Desde</option>
                        <?php
                        $result_ = $Vehiculos->getVehiculos();
                        while ($row = $result_->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['id_vehiculo']; ?>"><?php echo $row['id_vehiculo']; ?> - <?php echo $row['marca']; ?> - <?php echo $row['modelo']; ?> - chapa: <?php echo $row['placa']; ?></option>
                        <?php } ?>
                    </select>

                    <select class="form-select form-select-sm" id="vh" name="vh" value="999999" aria-label="Vehiculos">
                        <option value="999999" selected>Hasta</option>
                        <?php
                        $result_ = $Vehiculos->getVehiculos();
                        while ($row = $result_->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['id_vehiculo']; ?>"><?php echo $row['id_vehiculo']; ?> - <?php echo $row['marca']; ?> - <?php echo $row['modelo']; ?> - chapa: <?php echo $row['placa']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-sm col-md">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" id="Clientes">Cliente</span>
                    <select class="form-select form-select-sm" id="cd" name="cd" value="0" aria-label="Clientes">
                        <option value="0" selected>Desde</option>
                        <?php
                        $result_ = $Clientes->getClientes();
                        while ($row = $result_->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['id_cliente']; ?>"><?php echo $row['id_cliente']; ?> - <?php echo $row['cedula']; ?> - <?php echo $row['nombre_apellido']; ?></option>
                        <?php } ?>
                    </select>

                    <select class="form-select form-select-sm" id="ch" name="ch" value="999999" aria-label="Clientes">
                        <option value="999999" selected>Hasta</option>
                        <?php
                        $result_ = $Clientes->getClientes();
                        while ($row = $result_->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['id_cliente']; ?>"><?php echo $row['id_cliente']; ?> - <?php echo $row['cedula']; ?> - <?php echo $row['nombre_apellido']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-sm col-md">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" id="Usuarios">Usuario</span>
                    <select class="form-select form-select-sm" id="ud" name="ud" value="0" aria-label="Usuarios">
                        <option value="0" selected>Desde</option>
                        <?php
                        $result_ = $Usuarios->getUsuarios();
                        while ($row = $result_->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['id_usuario']; ?> - <?php echo $row['nombre']; ?> - <?php echo $row['apellido']; ?></option>
                        <?php } ?>
                    </select>

                    <select class="form-select form-select-sm" id="uh" name="uh" value="999999" aria-label="Usuarios">
                        <option value="999999" selected>Hasta</option>
                        <?php
                        $result_ = $Usuarios->getUsuarios();
                        while ($row = $result_->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['id_usuario']; ?> - <?php echo $row['nombre']; ?> - <?php echo $row['apellido']; ?></option>
                        <?php } ?>
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
            <div class="col-sm col-md">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" id="Estado">Estado</span>
                    <select class="form-select form-select-sm" id="ed" name="ed" value="AA" aria-label="Estado">
                        <option value="AA" selected>Desde</option>
                        <option value="AC">Activo</option>
                        <option value="IN">Inactivo</option>
                    </select>

                    <select class="form-select form-select-sm" id="eh" name="eh" value="ZZ" aria-label="Estado">
                        <option value="ZZ" selected>Hasta</option>
                        <option value="AC">Activo</option>
                        <option value="IN">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-1 col-md-1">
                <input type="hidden" name="_f" id="_f" value="1">
                <button type="submit" class="btn btn-sm btn-outline-primary">Filtrar</button>
            </div>
        </form>
        <!-- Tabla de Servicios -->
        <table class="table table-striped table-responsive table-bordered mt-2">
            <thead class="table-primary">
                <tr>
                    <th class="text-center"><strong>Descripcion</strong></th>
                    <th class="text-center"><strong>Vehiculo</strong></th>
                    <th class="text-center"><strong>Cliente</strong></th>
                    <th class="text-center"><strong>Mecanico</strong></th>
                    <th class="text-center"><strong>Estado</strong></th>
                    <th class="text-center"><strong>Fecha de Alta</strong></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($servicio = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $servicio['descripcion']; ?></td>
                        <td>
                            <?php
                            $result_ = $Vehiculos->getVehiculo($servicio['id_vehiculo']);
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
                            $result_ = $Clientes->getCliente($servicio['id_cliente']);
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
                            $result_ = $Usuarios->getUsuario($servicio['id_usuario']);
                            if ($result_->num_rows > 0) {
                                $row = $result_->fetch_assoc();
                                $nombre = $row['nombre'];
                                $apellido = $row['apellido'];
                                echo "$nombre $apellido";
                            }
                            ?>
                        </td>
                        <td class="text-center"><?php echo $servicio['estado']; ?></td>
                        <td class="text-center">
                            <?php
                            $timestamp = strtotime($servicio['creado_en']);
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