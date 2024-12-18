<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
//
include_once 'procesos/Presupuestos.php';
$Presupuestos = new Presupuestos();
include_once 'procesos/Clientes.php';
$Clientes = new Clientes();
include_once 'procesos/Usuarios.php';
$Usuarios = new Usuarios();
include_once 'procesos/Vehiculos.php';
$Vehiculos = new Vehiculos();
include_once 'procesos/Servicios.php';
$Servicios = new Servicios();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["id_presupuesto"]) && $_GET["id_presupuesto"] >= 0) {
        $result = $Presupuestos->getPresupuesto($_GET["id_presupuesto"]);
    } else {
        $result = $Presupuestos->getPresupuestos();
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["id_presupuesto"]) && $_POST["id_presupuesto"] >= 0) {
        $resultado = $Presupuestos->updatePresupuesto($_POST);

        if ($resultado > 0) {
            header("Location: gestion_presupuestos.php");
        } else {
            #header("Location: gestion_presupuestos.php");
            echo "Error al guardar el presupuesto.";
            $result = $Presupuestos->getPresupuesto($_POST["id_presupuesto"]);
        }
    } else {
        $resultado = $Presupuestos->insertPresupuesto($_POST);

        if ($resultado > 0) {
            header("Location: gestion_presupuestos.php");
        } else {
            #header("Location: gestion_presupuestos.php");
            echo "Error al guardar el presupuesto.";
            $result = $Presupuestos->getPresupuestos();
        }
    }
};
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Presupuestos</title>
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
        <h2>Gestión de Presupuestos</h2>
        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPresupuestoModal">Agregar Presupuesto</a>

        <!-- Tabla de Presupuestos -->
        <table class="table table-striped table-responsive caption-top">
            <caption>Lista de Presupuestos</caption>
            <thead class="table-primary">
                <tr>
                    <th>Servicio</th>
                    <th>Descripcion</th>
                    <th>Total Estimado</th>
                    <th>Costo Mano de Obra</th>
                    <th>Costo Repuestos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
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
                        <td><?php echo $presupuesto['total_estimado']; ?></td>
                        <td><?php echo $presupuesto['mano_obra']; ?></td>
                        <td><?php echo $presupuesto['repuestos']; ?></td>
                        <td><?php echo $presupuesto['estado']; ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updPresupuestoModal" onclick="loadData(<?php echo htmlspecialchars(json_encode($presupuesto)); ?>)">Editar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar Presupuestos -->
    <div class="modal fade" id="addPresupuestoModal" tabindex="-1" aria-labelledby="addPresupuestoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPresupuestoModalLabel">Agregar Presupuesto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="gestion_presupuestos.php" method="POST">
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripcion</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_servicio" class="form-label">Servicio</label>
                            <select class="form-select" id="id_servicio" name="id_servicio" required>
                                <option value="" selected>Seleccione un Servicio</option>
                                <?php
                                $servicios_result = $Servicios->getServicios();
                                while ($row = $servicios_result->fetch_assoc()) {
                                ?>
                                    <option value="<?php echo $row['id_servicio']; ?>"><?php echo $row['descripcion']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="total_estimado" class="form-label">Total Estimado</label>
                            <input type="number" class="form-control" id="total_estimado" name="total_estimado" required>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md col-sm">
                                <label for="mano_obra" class="form-label">Costo Mano de Obra</label>
                                <input type="number" class="form-control" id="mano_obra" name="mano_obra" required>
                            </div>
                            <div class="col-md col-sm">
                                <label for="repuestos" class="form-label">Costo Repuestos</label>
                                <input type="number" class="form-control" id="repuestos" name="repuestos" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar Presupuestos -->
    <div class="modal fade" id="updPresupuestoModal" tabindex="-1" aria-labelledby="updPresupuestoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updPresupuestoModalLabel">Modificar Presupuesto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="presupuesto-edit-form" action="gestion_presupuestos.php" method="POST">
                        <input type="hidden" name="id_presupuesto" id="edit-id_presupuesto">
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripcion</label>
                            <input type="text" class="form-control" id="edit-descripcion" name="descripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_servicio" class="form-label">Servicio</label>
                            <select class="form-select" id="edit-id_servicio" name="id_servicio" required>
                                <option value="" selected>Seleccione un Servicio</option>
                                <?php
                                $servicios_result = $Servicios->getServicios();
                                while ($row = $servicios_result->fetch_assoc()) {
                                ?>
                                    <option value="<?php echo $row['id_servicio']; ?>"><?php echo $row['descripcion']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="total_estimado" class="form-label">Total Estimado</label>
                            <input type="number" class="form-control" id="edit-total_estimado" name="total_estimado" required>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md col-sm">
                                <label for="mano_obra" class="form-label">Costo Mano de Obra</label>
                                <input type="number" class="form-control" id="edit-mano_obra" name="mano_obra" required>
                            </div>
                            <div class="col-md col-sm">
                                <label for="repuestos" class="form-label">Costo Repuestos</label>
                                <input type="number" class="form-control" id="edit-repuestos" name="repuestos" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="edit-estado" name="estado" required>
                                <option value="PENDIENTE">PENDIENTE</option>
                                <option value="APROBADO">APROBADO</option>
                                <option value="RECHAZADO">RECHAZADO</option>
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
    function loadData(presupuesto) {
        let presupuestoForm = document.getElementById("presupuesto-edit-form");
        presupuestoForm.querySelector('#edit-id_presupuesto').value = presupuesto.id_presupuesto;
        presupuestoForm.querySelector('#edit-descripcion').value = presupuesto.descripcion;
        presupuestoForm.querySelector('#edit-id_servicio').value = presupuesto.id_servicio;
        presupuestoForm.querySelector('#edit-total_estimado').value = presupuesto.total_estimado;
        presupuestoForm.querySelector('#edit-mano_obra').value = presupuesto.mano_obra;
        presupuestoForm.querySelector('#edit-repuestos').value = presupuesto.repuestos;
        presupuestoForm.querySelector('#edit-estado').value = presupuesto.estado;
    };
    //
</script>