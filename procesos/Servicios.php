<?php
class Servicios
{
    private $dbConnect = null;

    public function __construct()
    {
        include_once("Conexion.php");
        $Conexion = new Conexion();
        $this->dbConnect = $Conexion->conectar();
    }

    public function getNextIdServicio()
    {
        $next_id_servicio = 1;
        $conn = $this->dbConnect;
        // Función para obtener servicios
        $sql = "SELECT coalesce(max(id_servicio), 0) + 1 next_id_servicio FROM servicios";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $next_id_servicio = $row['next_id_servicio'];
        }
        if (!(isset($next_id_servicio) && $next_id_servicio >= 0)) {
            $next_id_servicio = 1;
        }

        return $next_id_servicio;
    }

    public function getServicios()
    {
        $conn = $this->dbConnect;
        // Función para obtener servicios
        $sql = "SELECT * FROM servicios";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getServicio($id_servicio)
    {
        $conn = $this->dbConnect;
        // Función para obtener servicios
        $sql = "SELECT * FROM servicios where id_servicio = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_servicio);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getServicioPorFiltro($id_vehiculo_desde, $id_vehiculo_hasta, $id_cliente_desde, $id_cliente_hasta, $id_usuario_desde, $id_usuario_hasta, $estado_desde, $estado_hasta, $creado_en_desde, $creado_en_hasta)
    {
        $conn = $this->dbConnect;
        // Función para obtener servicios
        $sql = "SELECT * FROM servicios where id_vehiculo >= ? and id_vehiculo <= ? and id_cliente >= ? and id_cliente <= ? and id_usuario >= ? and id_usuario <= ? and estado >= ? and estado <= ? and creado_en >= CAST(? AS DATE) and creado_en <= CAST(? AS DATE)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiiiissss", $id_vehiculo_desde, $id_vehiculo_hasta, $id_cliente_desde, $id_cliente_hasta, $id_usuario_desde, $id_usuario_hasta, $estado_desde, $estado_hasta, $creado_en_desde, $creado_en_hasta);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function insertServicio($_post)
    {
        $conn = $this->dbConnect;
        $id_servicio = $this->getNextIdServicio();
        $descripcion = mysqli_real_escape_string($conn, $_post['descripcion']);
        $id_vehiculo = mysqli_real_escape_string($conn, $_post['id_vehiculo']);
        $id_cliente = mysqli_real_escape_string($conn, $_post['id_cliente']);
        $id_usuario = mysqli_real_escape_string($conn, $_post['id_usuario']);
        // Insertar servicio
        $sql = "INSERT INTO servicios (id_servicio, descripcion, id_vehiculo, id_cliente, id_usuario) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("isiii", $id_servicio, $descripcion, $id_vehiculo, $id_cliente, $id_usuario);
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

    public function updateServicio($_post)
    {
        $conn = $this->dbConnect;
        // Obtener los datos del formulario
        $id_servicio = mysqli_real_escape_string($conn, $_post['id_servicio']);
        $descripcion = mysqli_real_escape_string($conn, $_post['descripcion']);
        $id_vehiculo = mysqli_real_escape_string($conn, $_post['id_vehiculo']);
        $id_cliente = mysqli_real_escape_string($conn, $_post['id_cliente']);
        $id_usuario = mysqli_real_escape_string($conn, $_post['id_usuario']);
        $estado = mysqli_real_escape_string($conn, $_post['estado']);

        // Insertar servicio
        $sql = "UPDATE servicios set descripcion = ?, id_vehiculo = ?, id_cliente = ?, id_usuario = ?, estado = ? where id_servicio = ?";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("sssssi", $descripcion, $id_vehiculo, $id_cliente, $id_usuario, $estado, $id_servicio);
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

    public function deleteServicio($id_servicio)
    {
        $conn = $this->dbConnect;
        // Función para obtener servicios
        $sql = "DELETE FROM servicios where id_servicio = ?";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("i", $id_servicio);
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
