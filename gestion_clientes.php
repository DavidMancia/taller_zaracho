<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
//
include_once 'procesos/Clientes.php';
$clientes = new Clientes();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["id_cliente"]) && $_GET["id_cliente"] >= 0) {
        $result = $clientes->getCliente($_GET["id_cliente"]);
    } else {
        $result = $clientes->getClientes();
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["id_cliente"]) && $_POST["id_cliente"] >= 0) {
        $resultado = $clientes->updateCliente($_POST);

        if ($resultado > 0) {
            header("Location: gestion_clientes.php");
        } else {
            #header("Location: gestion_clientes.php");
            echo "Error al guardar el cliente.";
            $result = $clientes->getCliente($_POST["id_cliente"]);
        }
    } else {
        $resultado = $clientes->insertCliente($_POST);

        if ($resultado > 0) {
            header("Location: gestion_clientes.php");
        } else {
            #header("Location: gestion_clientes.php");
            echo "Error al guardar el cliente.";
            $result = $clientes->getClientes();
        }
    }
};
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
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
        <h2>Gestión de Clientes</h2>
        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addClienteModal">Agregar Cliente</a>

        <!-- Tabla de clientes -->
        <table class="table table-striped table-responsive caption-top">
            <caption>Lista de Clientes</caption>
            <thead class="table-primary">
                <tr>
                    <th>Cedula</th>
                    <th>Nombre Apellido</th>
                    <th>Telefono</th>
                    <th>Direccion</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cliente = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $cliente['cedula']; ?></td>
                        <td><?php echo $cliente['nombre_apellido']; ?></td>
                        <td><?php echo $cliente['telefono']; ?></td>
                        <td><?php echo $cliente['direccion']; ?></td>
                        <td><?php echo $cliente['estado']; ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updClienteModal" onclick="loadData(<?php echo htmlspecialchars(json_encode($cliente)); ?>)">Editar</a>
                            <a href="gestion_vehiculos.php?id_cliente=<?php echo $cliente['id_cliente'] ?>" class="btn btn-primary btn-sm">Vehiculos</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar clientes -->
    <div class="modal fade" id="addClienteModal" tabindex="-1" aria-labelledby="addClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClienteModalLabel">Agregar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="gestion_clientes.php" method="POST">
                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cedula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre_apellido" class="form-label">Nombre Apellido</label>
                            <input type="text" class="form-control" id="nombre_apellido" name="nombre_apellido" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Telefono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Direccion</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar clientes -->
    <div class="modal fade" id="updClienteModal" tabindex="-1" aria-labelledby="updClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updClienteModalLabel">Modificar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="cliente-edit-form" action="gestion_clientes.php" method="POST">
                        <input type="hidden" name="id_cliente" id="id_cliente">
                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cedula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre_apellido" class="form-label">Nombre Apellido</label>
                            <input type="text" class="form-control" id="nombre_apellido" name="nombre_apellido" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Telefono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Direccion</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
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
    function loadData(cliente) {
        let clienteForm = document.getElementById("cliente-edit-form");
        clienteForm.querySelector('#id_cliente').value = cliente.id_cliente;
        clienteForm.querySelector('#cedula').value = cliente.cedula;
        clienteForm.querySelector('#nombre_apellido').value = cliente.nombre_apellido;
        clienteForm.querySelector('#telefono').value = cliente.telefono;
        clienteForm.querySelector('#direccion').value = cliente.direccion;
        clienteForm.querySelector('#estado').value = cliente.estado;
    };
</script>