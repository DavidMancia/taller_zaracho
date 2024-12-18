<?php
class Roles
{
    private $dbConnect = null;
    //
    public function __construct()
    {
        include_once("Conexion.php");
        $Conexion = new Conexion();
        $this->dbConnect = $Conexion->conectar();
    }

    public function getRolesActivos()
    {
        $conn = $this->dbConnect;
        // Función para obtener roles
        $sql = "SELECT * FROM rol where estado = 'AC'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getRoles()
    {
        $conn = $this->dbConnect;
        // Función para obtener roles
        $sql = "SELECT * FROM rol";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getRol($id_rol)
    {
        $conn = $this->dbConnect;
        // Función para obtener roles
        $sql = "SELECT * FROM rol where id_rol = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_rol);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function insertRol($_post)
    {
        $conn = $this->dbConnect;
        $nombre = mysqli_real_escape_string($conn, $_post['nombre']);
        $descripcion = mysqli_real_escape_string($conn, $_post['descripcion']);
        // Insertar rol
        $sql = "INSERT INTO rol (nombre, descripcion) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("ss", $nombre, $descripcion);
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

    public function updateRol($_post)
    {
        $conn = $this->dbConnect;
        // Obtener los datos del formulario
        $id_rol = mysqli_real_escape_string($conn, $_post['id_rol']);
        $nombre = mysqli_real_escape_string($conn, $_post['nombre']);
        $descripcion = mysqli_real_escape_string($conn, $_post['descripcion']);

        // Insertar rol
        $sql = "UPDATE rol set nombre = ?, descripcion = ? where id_rol = ?";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("ssssisi", $nombre, $descripcion, $id_rol);
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
