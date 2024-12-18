<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
//
include_once 'procesos/RolVentanas.php';
$rolventanas = new RolVentanas();
include_once("procesos/Roles.php");
$Roles = new Roles();
include_once("procesos/Ventanas.php");
$Ventanas = new Ventanas();
$rol_ventana_upd = null;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //
    $roles_result = $Roles->getRoles();
    $ventanas_result = $Ventanas->getVentanas();
    //
    if (isset($_GET["id_ventana"]) && $_GET["id_ventana"] >= 0) {
        $result = $rolventanas->getRolVentanaPorVentana($_GET["id_ventana"]);
    } else {
        $result = $rolventanas->getRolVentanas();
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["_method"]) && $_POST["_method"] == "UPD") {
        #echo $_POST;
        foreach ($_POST as $key => $value) {
            echo "$key is at $value \n";
        }
        $resultado = $rolventanas->updateRolVentana($_POST);

        if ($resultado > 0) {
            header("Location: gestion_RolVentanas.php");
        } else {
            #header("Location: gestion_RolVentanas.php");
            echo "Error al guardar el Rol de Ventana.";
            $result = $rolventanas->getRolVentanaPorVentana($_POST["id_ventana"]);
        }
    }
    if (isset($_POST["_method"]) && $_POST["_method"] == "ADD") {
        $resultado = $rolventanas->insertRolVentana($_POST);

        if ($resultado > 0) {
            header("Location: gestion_RolVentanas.php");
        } else {
            #header("Location: gestion_RolVentanas.php");
            echo "Error al guardar el Rol de Ventana.";
            $result = $rolventanas->getRolVentanas();
        }
    }
};
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Rol de Ventanas</title>
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
        <h2>Gestión de Rol de Ventanas</h2>
        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addRolVentanaModal">Agregar Rol de Ventanas</a>

        <!-- Tabla de Rol de Ventanas -->
        <table class="table table-striped table-responsive caption-top">
            <caption>Lista Rol de Ventanas</caption>
            <thead class="table-primary">
                <tr>
                    <th>Ventana</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($rolventana = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $rolventana['id_ventana']; ?></td>
                        <td>
                            <?php
                            $roles = $Roles->getRol($rolventana['id_rol']);
                            if ($roles->num_rows > 0) {
                                $rol = $roles->fetch_assoc();
                                echo $rol['nombre'];
                            }
                            ?>
                        </td>
                        <td><?php echo $rolventana['estado']; ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updRolVentanaModal" onclick="loadData(<?php echo htmlspecialchars(json_encode($rolventana)); ?>)">Editar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar rolventanas -->
    <div class="modal fade" id="addRolVentanaModal" tabindex="-1" aria-labelledby="addRolVentanaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRolVentanaModalLabel">Agregar Rol de Ventana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="gestion_RolVentanas.php" method="POST">
                        <input type="text" class="form-control" id="_method" name="_method" value="ADD" hidden>
                        <div class="mb-3">
                            <label for="id_ventana" class="form-label">Ventana</label>
                            <select class="form-select" id="id_ventana" name="id_ventana" value="" required>
                                <?php while ($ventana = $ventanas_result->fetch_assoc()) { ?>
                                    <option value="<?php echo $ventana['id_ventana']; ?>"><?php echo $ventana['id_ventana']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_rol" class="form-label">Rol</label>
                            <select class="form-select" id="id_rol" name="id_rol" required>
                                <?php while ($rol = $roles_result->fetch_assoc()) { ?>
                                    <option value="<?php echo $rol['id_rol']; ?>"><?php echo $rol['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar rolventanas -->
    <div class="modal fade" id="updRolVentanaModal" tabindex="-1" aria-labelledby="updRolVentanaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updRolVentanaModalLabel">Modificar Rol de Ventana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rolVentana-edit-form" action="gestion_RolVentanas.php" method="POST">
                        <input type="text" class="form-control" id="_method" name="_method" value="UPD" hidden>
                        <input type="text" class="form-control" id="id_rol" name="id_rol" hidden>
                        <div class="mb-3">
                            <label for="id_ventana" class="form-label">Ventana</label>
                            <input type="text" class="form-control" id="id_ventana" name="id_ventana" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="id_rol_" class="form-label">Rol</label>
                            <select class="form-select" id="id_rol_" name="id_rol_" disabled>
                                <?php
                                $roles_result = $Roles->getRoles();
                                while ($rol = $roles_result->fetch_assoc()) {
                                ?>
                                    <option value="<?php echo $rol['id_rol']; ?>"><?php echo $rol['nombre']; ?></option>
                                <?php } ?>
                            </select>
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
    function loadData(rolventana) {
        var rolventanaForm = document.getElementById("rolVentana-edit-form");
        rolventanaForm.querySelector('#id_ventana').value = rolventana.id_ventana;
        rolventanaForm.querySelector('#id_rol_').value = rolventana.id_rol;
        rolventanaForm.querySelector('#id_rol').value = rolventana.id_rol;
        rolventanaForm.querySelector('#estado').value = rolventana.estado;
    };
</script>