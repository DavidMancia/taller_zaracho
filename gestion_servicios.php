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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["id_servicio"]) && $_GET["id_servicio"] > 0 && isset($_GET["del_"]) && $_GET["del_"] > 0) {
        $resultado = $Servicios->deleteServicio($_GET["id_servicio"]);

        if ($resultado > 0) {
            header("Location: gestion_servicios.php");
        } else {
            #header("Location: gestion_servicios.php");
            die("Error al eliminar el servicio.");
            $result_serv = $Servicios->getServicio($_POST["id_servicio"]);
        }
    } else if (isset($_GET["id_servicio"]) && $_GET["id_servicio"] > 0) {
        $result_serv = $Servicios->getServicio($_GET["id_servicio"]);
    } else {
        $result_serv = $Servicios->getServicios();
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["id_servicio"]) && $_POST["id_servicio"] >= 0) {
        $resultado = $Servicios->updateServicio($_POST);

        if ($resultado > 0) {
            header("Location: gestion_servicios.php");
        } else {
            #header("Location: gestion_servicios.php");
            die("Error al guardar el servicio.");
            $result_serv = $Servicios->getServicio($_POST["id_servicio"]);
        }
    } else {
        $resultado = $Servicios->insertServicio($_POST);

        if ($resultado > 0) {
            header("Location: gestion_servicios.php");
        } else {
            #header("Location: gestion_servicios.php");
            die("Error al guardar el servicio.");
            $result_serv = $Servicios->getServicios();
        }
    }
};
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Servicios</title>
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
        <h2>Gestión de Servicios</h2>
        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addServicioModal">Agregar Servicio</a>

        <!-- Tabla de Servicios -->
        <table class="table table-striped table-responsive caption-top">
            <caption>Lista de Servicios</caption>
            <thead class="table-primary">
                <tr>
                    <th class="text-center">Descripcion</th>
                    <th class="text-center">Vehiculo</th>
                    <th class="text-center">Cliente</th>
                    <th class="text-center">Mecanico</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Posee Presupuesto</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($servicio = $result_serv->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $servicio['descripcion']; ?></td>
                        <td>
                            <?php
                            $result_vehi = $Vehiculos->getVehiculo($servicio['id_vehiculo']);
                            if ($result_vehi->num_rows > 0) {
                                $row = $result_vehi->fetch_assoc();
                                $marca = $row['marca'];
                                $modelo = $row['modelo'];
                                $placa = $row['placa'];
                                echo "$marca - $modelo - chapa: $placa";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $result_clie = $Clientes->getCliente($servicio['id_cliente']);
                            if ($result_clie->num_rows > 0) {
                                $row = $result_clie->fetch_assoc();
                                $cedula = $row['cedula'];
                                $nombre_apellido = $row['nombre_apellido'];
                                echo "$cedula - $nombre_apellido";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $result_usua = $Usuarios->getUsuario($servicio['id_usuario']);
                            if ($result_usua->num_rows > 0) {
                                $row = $result_usua->fetch_assoc();
                                $nombre = $row['nombre'];
                                $apellido = $row['apellido'];
                                echo "$nombre $apellido";
                            }
                            ?>
                        </td>
                        <td class="text-center"><?php echo $servicio['estado']; ?></td>
                        <td class="text-center">
                            <?php
                            $result_presu = $Presupuestos->getPresupuestoPorServicio($servicio['id_servicio']);
                            if ($result_presu->num_rows > 0) {
                                $row = $result_presu->fetch_assoc();
                                $id_presupuesto = $row['id_presupuesto'];
                                if ($id_presupuesto > 0) {
                                    echo "SI";
                                } else {
                                    echo "NO";
                                }
                            } else {
                                echo "NO";
                            }
                            ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updServicioModal" onclick="loadData(<?php echo htmlspecialchars(json_encode($servicio)); ?>)">Editar</a>
                                <a href="gestion_vehiculos.php?id_vehiculo=<?php echo $servicio['id_vehiculo'] ?>" class="btn btn-primary btn-sm">Vehiculo</a>
                                <a href="gestion_servicios.php?id_servicio=<?php echo $servicio['id_servicio'] ?>&del_=1" class="btn btn-danger btn-sm">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar Servicios -->
    <div class="modal fade" id="addServicioModal" tabindex="-1" aria-labelledby="addServicioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addServicioModalLabel">Agregar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="gestion_servicios.php" method="POST">
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripcion</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                        </div>
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
                            <label for="id_vehiculo" class="form-label">Vehiculo</label>
                            <select class="form-select" id="id_vehiculo" name="id_vehiculo" required>
                                <option value="" selected>Seleccione un Cliente primero</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_usuario" class="form-label">Mecanico</label>
                            <select class="form-select" id="id_usuario" name="id_usuario" required>
                                <?php
                                $usuarios_result = $Usuarios->getUsuarios();
                                while ($row = $usuarios_result->fetch_assoc()) {
                                ?>
                                    <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['nombre']; ?> <?php echo $row['apellido']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar Servicios -->
    <div class="modal fade" id="updServicioModal" tabindex="-1" aria-labelledby="updServicioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updServicioModalLabel">Modificar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="servicio-edit-form" action="gestion_servicios.php" method="POST">
                        <input type="hidden" name="id_servicio" id="edit-id_servicio">
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripcion</label>
                            <input type="text" class="form-control" id="edit-descripcion" name="descripcion" required>
                        </div>
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
                            <label for="id_vehiculo" class="form-label">Vehiculo</label>
                            <select class="form-select" id="edit-id_vehiculo" name="id_vehiculo" required>
                                <option value="" selected>Seleccione un Cliente primero</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_usuario" class="form-label">Mecanico</label>
                            <select class="form-select" id="edit-id_usuario" name="id_usuario" required>
                                <?php
                                $usuarios_result = $Usuarios->getUsuarios();
                                while ($row = $usuarios_result->fetch_assoc()) {
                                ?>
                                    <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['nombre']; ?> <?php echo $row['apellido']; ?></option>
                                <?php } ?>
                            </select>
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
    //
    let retrieveVehiculos = function(clienteId, id_vehiculoDropdown, id_vehiculo) {
        const vehiculoDropdown = document.getElementById(id_vehiculoDropdown);

        // Si no hay cliente seleccionada, limpiar el dropdown de vehiculo
        if (!clienteId) {
            vehiculoDropdown.innerHTML = '<option value="">Seleccione un cliente primero</option>';
            return;
        }

        // Realizar una solicitud AJAX al servidor para obtener los vehiculos
        fetch('procesos/Vehiculos.php?id_cliente=' + clienteId + '&_acction=DROPDOWN')
            .then(response => response.json()) // Convertir respuesta a JSON
            .then(data => {
                // Limpiar el dropdown de vehiculo
                vehiculoDropdown.innerHTML = '';

                // Agregar una opción por defecto
                vehiculoDropdown.innerHTML = '<option value="">Seleccione un vehiculo</option>';

                // Llenar el dropdown con los datos recibidos
                data.forEach(vehiculo => {
                    const option = document.createElement('option');
                    option.value = vehiculo.id_vehiculo;
                    option.textContent = vehiculo.marca + " - " + vehiculo.modelo + " - chapa: " + vehiculo.placa;
                    if (id_vehiculo == vehiculo.id_vehiculo) {
                        option.selected = true;
                    };
                    vehiculoDropdown.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al obtener los vehiculos:', error);
            });
    };
    //
    function loadData(servicio) {
        let servicioForm = document.getElementById("servicio-edit-form");
        servicioForm.querySelector('#edit-id_servicio').value = servicio.id_servicio;
        servicioForm.querySelector('#edit-descripcion').value = servicio.descripcion;
        servicioForm.querySelector('#edit-id_vehiculo').value = servicio.id_vehiculo;
        servicioForm.querySelector('#edit-id_cliente').value = servicio.id_cliente;
        servicioForm.querySelector('#edit-id_usuario').value = servicio.id_usuario;
        servicioForm.querySelector('#edit-estado').value = servicio.estado;
        retrieveVehiculos(servicio.id_cliente, 'edit-id_vehiculo', servicio.id_vehiculo);
    };
    //
    document.getElementById('id_cliente').addEventListener('change', function() {
        retrieveVehiculos(this.value, 'id_vehiculo', null);
    });
    //
    document.getElementById('edit-id_cliente').addEventListener('change', function() {
        retrieveVehiculos(this.value, 'edit-id_vehiculo', null);
    });
</script>