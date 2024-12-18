<?php
class Usuarios
{
    private $dbConnect = null;
    //id_usuario, nombre, apellido, cedula, contraseña, rol, estado, fch_estado
    public function __construct()
    {
        include_once("Conexion.php");
        $Conexion = new Conexion();
        $this->dbConnect = $Conexion->conectar();
    }

    public function getNextIdUsuario()
    {
        $next_id_usuario = 1;
        $conn = $this->dbConnect;
        // Función para obtener usuarios
        $sql = "SELECT coalesce(max(id_usuario), 0) + 1 next_id_usuario FROM usuarios";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $next_id_usuario = $row['next_id_usuario'];
        }
        if (!(isset($next_id_usuario) && $next_id_usuario >= 0)) {
            $next_id_usuario = 1;
        }

        return $next_id_usuario;
    }

    public function getUsuarios()
    {
        $conn = $this->dbConnect;
        // Función para obtener usuarios
        $sql = "SELECT * FROM usuarios";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getUsuario($id_usuario)
    {
        $conn = $this->dbConnect;
        // Función para obtener usuarios
        $sql = "SELECT * FROM usuarios where id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getUsuarioPorCedula($cedula)
    {
        $conn = $this->dbConnect;
        // Función para obtener usuarios
        $sql = "SELECT * FROM usuarios where cedula = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getUsuarioRolPorCedula($_post)
    {
        $conn = $this->dbConnect;
        // Obtener los valores del formulario
        $cedula = mysqli_real_escape_string($conn, $_post['cedula']);
        $contraseña = mysqli_real_escape_string($conn, $_post['contraseña']);
        // Función para obtener usuarios
        $sql = "SELECT u.id_usuario, u.nombre, u.apellido, u.cedula, u.rol, r.nombre AS rol_nombre, u.contraseña, ? as contraseña_in FROM usuarios u JOIN rol r ON u.rol = r.id_rol WHERE u.cedula = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $contraseña, $cedula);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function insertUsuario($_post)
    {
        $conn = $this->dbConnect;
        $id_usuario = $this->getNextIdUsuario();
        $nombre = mysqli_real_escape_string($conn, $_post['nombre']);
        $apellido = mysqli_real_escape_string($conn, $_post['apellido']);
        $cedula = mysqli_real_escape_string($conn, $_post['cedula']);
        $contraseña = mysqli_real_escape_string($conn, $_post['contraseña']);
        //
        $contraseña = password_hash($contraseña, PASSWORD_DEFAULT);  // Contraseña encriptada
        //
        $rol = mysqli_real_escape_string($conn, $_post['rol']);
        // Insertar usuario
        $sql = "INSERT INTO usuarios (id_usuario, nombre, apellido, cedula, contraseña, rol) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("issssi", $id_usuario, $nombre, $apellido, $cedula, $contraseña, $rol);
        if (false === $result) {
            die('bind_param() failed');
        }
        $result = $stmt->execute();
        if (false === $result) {
            die('execute() failed: ' . $stmt->error);
        }
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }

    public function updateUsuario($_post)
    {
        $conn = $this->dbConnect;
        // Obtener los datos del formulario
        $id_usuario = mysqli_real_escape_string($conn, $_post['id_usuario']);
        $nombre = mysqli_real_escape_string($conn, $_post['nombre']);
        $apellido = mysqli_real_escape_string($conn, $_post['apellido']);
        $cedula = mysqli_real_escape_string($conn, $_post['cedula']);
        $contraseña = mysqli_real_escape_string($conn, $_post['contraseña']);
        //
        $contraseña = password_hash($contraseña, PASSWORD_DEFAULT);  // Contraseña encriptada
        //
        $rol = mysqli_real_escape_string($conn, $_post['rol']);
        $estado = mysqli_real_escape_string($conn, $_post['estado']);

        // Insertar usuario
        $sql = "UPDATE usuarios set nombre = ?, apellido = ?, cedula = ?, contraseña = ?, rol = ?, estado = ? where id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("ssssisi", $nombre, $apellido, $cedula, $contraseña, $rol, $estado, $id_usuario);
        if (false === $result) {
            die('bind_param() failed');
        }
        $result = $stmt->execute();
        if (false === $result) {
            die('execute() failed: ' . $stmt->error);
        }
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }
}
