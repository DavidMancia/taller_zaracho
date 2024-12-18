<?php
class Presupuestos
{
    private $dbConnect = null;

    public function __construct()
    {
        include_once("Conexion.php");
        $Conexion = new Conexion();
        $this->dbConnect = $Conexion->conectar();
    }

    public function getNextIdPresupuesto()
    {
        $next_id_presupuesto = 1;
        $conn = $this->dbConnect;
        // Función para obtener presupuestos
        $sql = "SELECT coalesce(max(id_presupuesto), 0) + 1 next_id_presupuesto FROM presupuestos";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $next_id_presupuesto = $row['next_id_presupuesto'];
        }
        if (!(isset($next_id_presupuesto) && $next_id_presupuesto >= 0)) {
            $next_id_presupuesto = 1;
        }

        return $next_id_presupuesto;
    }

    public function getPresupuestos()
    {
        $conn = $this->dbConnect;
        // Función para obtener presupuestos
        $sql = "SELECT * FROM presupuestos";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getPresupuesto($id_presupuesto)
    {
        $conn = $this->dbConnect;
        // Función para obtener presupuestos
        $sql = "SELECT * FROM presupuestos where id_presupuesto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_presupuesto);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getPresupuestoPorServicio($id_servicio)
    {
        $conn = $this->dbConnect;
        // Función para obtener presupuestos
        $sql = "SELECT * FROM presupuestos where id_servicio = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_servicio);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getPresupuestoPorFiltro($id_servicio_desde, $id_servicio_hasta, $total_estimado_desde, $total_estimado_hasta, $mano_obra_desde, $mano_obra_hasta, $repuestos_desde, $repuestos_hasta, $estado_desde, $estado_hasta, $creado_en_desde, $creado_en_hasta)
    {
        // echo "$id_servicio_desde, $id_servicio_hasta, $total_estimado_desde, $total_estimado_hasta, $mano_obra_desde, $mano_obra_hasta, $repuestos_desde, $repuestos_hasta, $estado_desde, $estado_hasta, $creado_en_desde, $creado_en_hasta";
        $conn = $this->dbConnect;
        // Función para obtener presupuestos
        $sql = "SELECT * FROM presupuestos where id_servicio >= ? and id_servicio <= ? and total_estimado >= ? and total_estimado <= ? and mano_obra >= ? and mano_obra <= ? and repuestos >= ? and repuestos <= ? and estado >= ? and estado <= ? and CAST(creado_en AS DATE) >= CAST(? AS DATE) and CAST(creado_en AS DATE) <= CAST(? AS DATE)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiiiiiissss", $id_servicio_desde, $id_servicio_hasta, $total_estimado_desde, $total_estimado_hasta, $mano_obra_desde, $mano_obra_hasta, $repuestos_desde, $repuestos_hasta, $estado_desde, $estado_hasta, $creado_en_desde, $creado_en_hasta);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function insertPresupuesto($_post)
    {
        $conn = $this->dbConnect;
        $id_presupuesto = $this->getNextIdPresupuesto();
        $id_servicio = mysqli_real_escape_string($conn, $_post['id_servicio']);
        $descripcion = mysqli_real_escape_string($conn, $_post['descripcion']);
        $total_estimado = mysqli_real_escape_string($conn, $_post['total_estimado']);
        $mano_obra = mysqli_real_escape_string($conn, $_post['mano_obra']);
        $repuestos = mysqli_real_escape_string($conn, $_post['repuestos']);
        // Insertar presupuesto
        $sql = "INSERT INTO presupuestos (id_presupuesto, id_servicio, descripcion, total_estimado, mano_obra, repuestos) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("iisiii", $id_presupuesto, $id_servicio, $descripcion, $total_estimado, $mano_obra, $repuestos);
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

    public function updatePresupuesto($_post)
    {
        $conn = $this->dbConnect;
        // Obtener los datos del formulario
        $id_presupuesto = mysqli_real_escape_string($conn, $_post['id_presupuesto']);
        $id_servicio = mysqli_real_escape_string($conn, $_post['id_servicio']);
        $descripcion = mysqli_real_escape_string($conn, $_post['descripcion']);
        $total_estimado = mysqli_real_escape_string($conn, $_post['total_estimado']);
        $mano_obra = mysqli_real_escape_string($conn, $_post['mano_obra']);
        $repuestos = mysqli_real_escape_string($conn, $_post['repuestos']);
        $estado = mysqli_real_escape_string($conn, $_post['estado']);

        // Insertar presupuesto
        $sql = "UPDATE presupuestos set id_servicio = ?, descripcion = ?, total_estimado = ?, mano_obra = ?, repuestos = ?, estado = ? where id_presupuesto = ?";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("isiiisi", $id_servicio, $descripcion, $total_estimado, $mano_obra, $repuestos, $estado, $id_presupuesto);
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
