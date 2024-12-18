<?php

include_once("config/config.php");

// Comprobar si se enviaron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    //
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);  // Contraseña encriptada
    //
    $id_rol = $_POST['id_rol'];

    // Insertar el nuevo usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre, apellido, email, contraseña, rol) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $apellido, $email, $contraseña, $id_rol);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $mensaje = "Usuario registrado correctamente.";
        header("Location: login.php");
    } else {
        $mensaje = "Hubo un error al registrar el usuario.";
    }
}

// Cerrar conexión
$conn->close();
