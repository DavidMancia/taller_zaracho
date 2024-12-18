<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
//
include_once 'procesos/Usuarios.php';
$usuarios = new Usuarios();
include_once("procesos/Roles.php");
$Roles = new Roles();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $roles_result = $Roles->getRoles();
    if (isset($_GET["id_usuario"]) && $_GET["id_usuario"] >= 0) {
        $result = $usuarios->getUsuario($_GET["id_usuario"]);
    } else {
        $result = $usuarios->getUsuarios();
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["id_usuario"]) && $_POST["id_usuario"] >= 0) {
        $resultado = $usuarios->updateUsuario($_POST);

        if ($resultado > 0) {
            header("Location: gestion_usuarios.php");
        } else {
            #header("Location: gestion_usuarios.php");
            echo "Error al guardar el usuario.";
            $result = $usuarios->getUsuario($_POST["id_usuario"]);
        }
    } else {
        $resultado = $usuarios->insertUsuario($_POST);

        if ($resultado > 0) {
            header("Location: gestion_usuarios.php");
        } else {
            #header("Location: gestion_usuarios.php");
            echo "Error al guardar el usuario.";
            $result = $usuarios->getUsuarios();
        }
    }
};
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
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
        <h2>Gestión de Usuarios</h2>
        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUsuarioModal">Agregar Usuario</a>

        <!-- Tabla de usuarios -->
        <table class="table table-striped table-responsive caption-top">
            <caption>Lista de Usuarios</caption>
            <thead class="table-primary">
                <tr>
                    <th>Cedula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $usuario['cedula']; ?></td>
                        <td><?php echo $usuario['nombre']; ?></td>
                        <td><?php echo $usuario['apellido']; ?></td>
                        <td>
                            <?php
                            $roles = $Roles->getRol($usuario['rol']);
                            if ($roles->num_rows > 0) {
                                $rol = $roles->fetch_assoc();
                                echo $rol['nombre'];
                            }
                            ?>
                        </td>
                        <td><?php echo $usuario['estado']; ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updUsuarioModal" onclick="loadData(<?php echo htmlspecialchars(json_encode($usuario)); ?>)">Editar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar usuarios -->
    <div class="modal fade" id="addUsuarioModal" tabindex="-1" aria-labelledby="addUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUsuarioModalLabel">Agregar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="gestion_usuarios.php" method="POST">
                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cedula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="mb-3">
                            <label for="contraseña" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                        </div>
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-select" id="rol" name="rol" required>
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

    <!-- Modal para agregar usuarios -->
    <div class="modal fade" id="updUsuarioModal" tabindex="-1" aria-labelledby="updUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updUsuarioModalLabel">Modificar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="usuario-edit-form" action="gestion_usuarios.php" method="POST">
                        <input type="hidden" name="id_usuario" id="id_usuario">
                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cedula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-select" id="rol" name="rol" required>
                                <?php while ($rol = $roles_result->fetch_assoc()) { ?>
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
    function loadData(usuario) {
        let usuarioForm = document.getElementById("usuario-edit-form");
        usuarioForm.querySelector('#id_usuario').value = usuario.id_usuario;
        usuarioForm.querySelector('#cedula').value = usuario.cedula;
        usuarioForm.querySelector('#nombre').value = usuario.nombre;
        usuarioForm.querySelector('#apellido').value = usuario.apellido;
        usuarioForm.querySelector('#rol').value = usuario.rol;
    };
</script>