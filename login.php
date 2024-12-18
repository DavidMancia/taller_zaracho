<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: index.php"); // Si ya está logueado, redirige a la página principal.
    exit();
}

include_once("procesos/usuarios.php");
$usuarios = new Usuarios();

// Verificar si el usuario ha cerrado sesión
if (isset($_GET['logged_out']) && $_GET['logged_out'] == 1) {
    echo '<div class="alert alert-success" role="alert">Has cerrado sesión con éxito.</div>';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resultado = $usuarios->getUsuarioRolPorCedula($_POST);

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        // Verificar la contraseña (aquí asumimos que la contraseña está cifrada)
        if (password_verify($usuario['contraseña_in'], $usuario['contraseña'])) {
            // Iniciar sesión
            $_SESSION['usuario'] = [
                'id_usuario' => $usuario['id_usuario'],
                'nombre' => $usuario['nombre'],
                'apellido' => $usuario['apellido'],
                'cedula' => $usuario['cedula'],
                'rol' => $usuario['rol'],
                'rol_nombre' => $usuario['rol_nombre']
            ];
            header("Location: index.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "La cedula no está registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Taller Zaracho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container container-fluid">
        <!-- Mostrar errores si los hay -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <!--  -->
        <section class="p-3 p-md-4 p-xl-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 bsb-tpl-bg-platinum">
                        <div class="d-flex flex-column justify-content-between h-100 p-3 p-md-4 p-xl-5">
                            <h3 class="m-0">¡Te damos la Bienvenida al Taller Zaracho!</h3>
                            <img class="img-fluid rounded mx-auto my-2" loading="lazy" src="./assets/img/bsb-logo.jpg" width="245" height="80" alt="BootstrapBrain Logo">
                            <p class="mb-1">En <strong>Taller Zaracho</strong> nos especializamos en brindar soluciones rápidas, confiables y de alta calidad para el mantenimiento y reparación de vehículos. Contamos con un equipo de técnicos expertos y herramientas de última generación para garantizar que tu vehículo esté siempre en las mejores condiciones. </p>
                            <p class="mb-0">¡Tu vehículo, en las mejores manos!</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 bsb-tpl-bg-lotion">
                        <div class="p-3 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-5">
                                        <h3>Acceder</h3>
                                    </div>
                                </div>
                            </div>
                            <form action="login.php" method="POST">
                                <div class="row gy-3 gy-md-4 overflow-hidden">
                                    <div class="col-12">
                                        <label for="cedula" class="form-label">Cedula <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="cedula" id="cedula" placeholder="123456" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="contraseña" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="contraseña" id="contraseña" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn bsb-btn-xl btn-primary" type="submit">Inicie sesión ahora</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>