<?php
class Ventanas
{
    private $dbConnect = null;
    //
    public function __construct()
    {
        include_once("Conexion.php");
        $Conexion = new Conexion();
        $this->dbConnect = $Conexion->conectar();
    }

    public function getVentanas()
    {
        $conn = $this->dbConnect;
        // Función para obtener ventanas
        $sql = "SELECT * FROM ventanas";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getVentanasPorRolGrupo($id_rol, $grupo)
    {
        $conn = $this->dbConnect;
        // Función para obtener ventanas
        $sql = "SELECT distinct vent.id_ventana, vent.descripcion FROM ventanas vent, rol_ventanas rove where rove.id_ventana=vent.id_ventana and vent.estado='AC' and rove.estado='AC' and rove.id_rol = ? and vent.grupo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $id_rol, $grupo);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getGruposVentanasPorRol($id_rol)
    {
        $conn = $this->dbConnect;
        // Función para obtener ventanas
        $sql = "SELECT distinct vent.grupo FROM ventanas vent, rol_ventanas rove where rove.id_ventana=vent.id_ventana and vent.estado='AC' and rove.estado='AC' and rove.id_rol = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_rol);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getVentana($id_ventana)
    {
        $conn = $this->dbConnect;
        // Función para obtener ventanas
        $sql = "SELECT * FROM ventanas where id_ventana = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_ventana);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function insertVentana($_post)
    {
        $conn = $this->dbConnect;
        $id_ventana = mysqli_real_escape_string($conn, $_post['id_ventana']);
        $descripcion = mysqli_real_escape_string($conn, $_post['descripcion']);
        // Insertar ventana
        $sql = "INSERT INTO ventanas (id_ventana, descripcion) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("ss", $id_ventana, $descripcion);
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

    public function updateVentana($_post)
    {
        $conn = $this->dbConnect;
        // Obtener los datos del formulario
        $id_ventana = mysqli_real_escape_string($conn, $_post['id_ventana']);
        $descripcion = mysqli_real_escape_string($conn, $_post['descripcion']);
        $estado = mysqli_real_escape_string($conn, $_post['estado']);

        // Insertar ventana
        $sql = "UPDATE ventanas set descripcion = ?, estado = ? where id_ventana = ?";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("sss", $descripcion, $estado, $id_ventana);
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
