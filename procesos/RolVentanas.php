<?php
class RolVentanas
{
    private $dbConnect = null;

    public function __construct()
    {
        include_once("Conexion.php");
        $Conexion = new Conexion();
        $this->dbConnect = $Conexion->conectar();
    }

    public function getRolVentanas()
    {
        $conn = $this->dbConnect;
        // Función para obtener rol_ventanas
        $sql = "SELECT * FROM rol_ventanas";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getRolVentanaPorRol($id_rol)
    {
        $conn = $this->dbConnect;
        // Función para obtener rol_ventanas
        $sql = "SELECT * FROM rol_ventanas where id_rol = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_rol);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getRolVentanaPorVentana($id_ventana)
    {
        $conn = $this->dbConnect;
        // Función para obtener rol_ventanas
        $sql = "SELECT * FROM rol_ventanas where id_ventana = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_ventana);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function insertRolVentana($_post)
    {
        $conn = $this->dbConnect;
        $id_ventana = mysqli_real_escape_string($conn, $_post['id_ventana']);
        $id_rol = mysqli_real_escape_string($conn, $_post['id_rol']);
        // Insertar rolventana
        $sql = "INSERT INTO rol_ventanas (id_ventana, id_rol) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("si", $id_ventana, $id_rol);
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

    public function updateRolVentana($_post)
    {
        $conn = $this->dbConnect;
        // Obtener los datos del formulario
        $id_ventana = mysqli_real_escape_string($conn, $_post['id_ventana']);
        $id_rol = mysqli_real_escape_string($conn, $_post['id_rol']);
        $estado = mysqli_real_escape_string($conn, $_post['estado']);

        // Insertar rolventana
        $sql = "UPDATE rol_ventanas set estado = ? where id_ventana = ? and id_rol = ?";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("ssi", $estado, $id_ventana, $id_rol);
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
