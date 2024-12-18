<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
//
include_once 'procesos/Clientes.php';
$Clientes = new Clientes();
include_once 'procesos/Usuarios.php';
$Usuarios = new Usuarios();
include_once 'procesos/Vehiculos.php';
$Vehiculos = new Vehiculos();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["id_vehiculo"]) && $_GET["id_vehiculo"] >= 0) {
        $result = $Vehiculos->getVehiculo($_GET["id_vehiculo"]);

    } else if (isset($_GET["id_cliente"]) && $_GET["id_cliente"] >= 0) {
        $result = $Vehiculos->getVehiculoPorCliente($_GET["id_cliente"]);
        
    } else {
        $result = $Vehiculos->getVehiculos();
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["id_vehiculo"]) && $_POST["id_vehiculo"] >= 0) {
        $resultado = $Vehiculos->updateVehiculo($_POST);

        if ($resultado > 0) {
            header("Location: gestion_vehiculos.php");
        } else {
            #header("Location: gestion_vehiculos.php");
            echo "Error al guardar el vehiculo.";
            $result = $Vehiculos->getVehiculo($_POST["id_vehiculo"]);
        }
    } else {
        $resultado = $Vehiculos->insertVehiculo($_POST);

        if ($resultado > 0) {
            header("Location: gestion_vehiculos.php");
        } else {
            #header("Location: gestion_vehiculos.php");
            echo "Error al guardar el vehiculo.";
            $result = $Vehiculos->getVehiculos();
        }
    }
};
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Vehiculos</title>
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
    <div class="container bg-white mt-3">
        <h2>Gestión de Vehiculos</h2>
        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addVehiculoModal">Agregar Vehiculo</a>

        <!-- Tabla de Vehiculos -->
        <table class="table table-striped table-responsive caption-top">
            <caption>Lista de Vehiculos</caption>
            <thead class="table-primary">
                <tr>
                    <th>Cliente</th>
                    <th>Marca</th>
                    <th>Anio</th>
                    <th>Placa</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($vehiculo = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php
                            $cliente = $Clientes->getCliente($vehiculo['id_cliente']);
                            if ($cliente->num_rows > 0) {
                                $row = $cliente->fetch_assoc();
                                echo $row['nombre_apellido'];
                            }
                            ?>
                        </td>
                        <td><?php echo $vehiculo['marca']; ?></td>
                        <td><?php echo $vehiculo['anio']; ?></td>
                        <td><?php echo $vehiculo['placa']; ?></td>
                        <td><?php echo $vehiculo['estado']; ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updVehiculoModal" onclick="loadData(<?php echo htmlspecialchars(json_encode($vehiculo)); ?>)">Editar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar Vehiculos -->
    <div class="modal fade" id="addVehiculoModal" tabindex="-1" aria-labelledby="addVehiculoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVehiculoModalLabel">Agregar Vehiculo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="gestion_vehiculos.php" method="POST">
                        <div class="mb-3">
                            <label for="id_cliente" class="form-label">Cliente</label>
                            <select class="form-select" id="id_cliente" name="id_cliente" required>
                                <option value="" selected>Seleccione un Cliente</option>
                                <?php
                                $clientes_result = $Clientes->getClientes();
                                while ($row = $clientes_result->fetch_assoc()) {
                                ?>
                                    <option value="<?php echo $row['id_cliente']; ?>"><?php echo $row['cedula']; ?> - <?php echo $row['nombre_apellido']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="marca" name="marca" required>
                        </div>
                        <div class="mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="modelo" name="modelo" required>
                        </div>
                        <div class="mb-3">
                            <label for="anio" class="form-label">Año</label>
                            <input type="number" class="form-control" id="anio" name="anio" required>
                        </div>
                        <div class="mb-3">
                            <label for="placa" class="form-label">Chapa</label>
                            <input type="text" class="form-control" id="placa" name="placa" required oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar Vehiculos -->
    <div class="modal fade" id="updVehiculoModal" tabindex="-1" aria-labelledby="updVehiculoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updVehiculoModalLabel">Modificar Vehiculo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="vehiculo-edit-form" action="gestion_vehiculos.php" method="POST">
                        <input type="hidden" name="id_vehiculo" id="edit-id_vehiculo">
                        <div class="mb-3">
                            <label for="id_cliente" class="form-label">Cliente</label>
                            <select class="form-select" id="edit-id_cliente" name="id_cliente" required>
                                <option value="" selected>Seleccione un Cliente</option>
                                <?php
                                $clientes_result = $Clientes->getClientes();
                                while ($row = $clientes_result->fetch_assoc()) {
                                ?>
                                    <option value="<?php echo $row['id_cliente']; ?>"><?php echo $row['cedula']; ?> - <?php echo $row['nombre_apellido']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="edit-marca" name="marca" required>
                        </div>
                        <div class="mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="edit-modelo" name="modelo" required>
                        </div>
                        <div class="mb-3">
                            <label for="anio" class="form-label">Año</label>
                            <input type="number" class="form-control" id="edit-anio" name="anio" required>
                        </div>
                        <div class="mb-3">
                            <label for="placa" class="form-label">Chapa</label>
                            <input type="text" class="form-control" id="edit-placa" name="placa" required oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="edit-estado" name="estado" required>
                                <option value="AC">Activo</option>
                                <option value="IN">Inactivo</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<script>
    function loadData(vehiculo) {
        let vehiculoForm = document.getElementById("vehiculo-edit-form");
        vehiculoForm.querySelector('#edit-id_vehiculo').value = vehiculo.id_vehiculo;
        vehiculoForm.querySelector('#edit-id_cliente').value = vehiculo.id_cliente;
        vehiculoForm.querySelector('#edit-marca').value = vehiculo.marca;
        vehiculoForm.querySelector('#edit-modelo').value = vehiculo.modelo;
        vehiculoForm.querySelector('#edit-anio').value = vehiculo.anio;
        vehiculoForm.querySelector('#edit-placa').value = vehiculo.placa;
        vehiculoForm.querySelector('#edit-estado').value = vehiculo.estado;
    };
</script>