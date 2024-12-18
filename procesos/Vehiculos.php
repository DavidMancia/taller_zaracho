<?php
class Vehiculos
{
    private $dbConnect = null;

    public function __construct()
    {
        include_once("Conexion.php");
        $Conexion = new Conexion();
        $this->dbConnect = $Conexion->conectar();
    }

    public function getNextIdVehiculo()
    {
        $next_id_vehiculo = 1;
        $conn = $this->dbConnect;
        // Función para obtener vehiculos
        $sql = "SELECT coalesce(max(id_vehiculo), 0) + 1 next_id_vehiculo FROM vehiculos";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $next_id_vehiculo = $row['next_id_vehiculo'];
        }
        if (!(isset($next_id_vehiculo) && $next_id_vehiculo >= 0)) {
            $next_id_vehiculo = 1;
        }

        return $next_id_vehiculo;
    }

    public function getVehiculos()
    {
        $conn = $this->dbConnect;
        // Función para obtener vehiculos
        $sql = "SELECT * FROM vehiculos";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getVehiculo($id_vehiculo)
    {
        $conn = $this->dbConnect;
        // Función para obtener vehiculos
        $sql = "SELECT * FROM vehiculos where id_vehiculo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_vehiculo);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getVehiculoPorCliente($id_cliente)
    {
        $conn = $this->dbConnect;
        // Función para obtener vehiculos
        $sql = "SELECT * FROM vehiculos where id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function insertVehiculo($_post)
    {
        $conn = $this->dbConnect;
        $id_vehiculo = $this->getNextIdVehiculo();
        $id_cliente = mysqli_real_escape_string($conn, $_post['id_cliente']);
        $marca = mysqli_real_escape_string($conn, $_post['marca']);
        $modelo = mysqli_real_escape_string($conn, $_post['modelo']);
        $anio = mysqli_real_escape_string($conn, $_post['anio']);
        $placa = mysqli_real_escape_string($conn, $_post['placa']);
        // Insertar vehiculo
        $sql = "INSERT INTO vehiculos (id_vehiculo, id_cliente, marca, modelo, anio, placa) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("iissis", $id_vehiculo, $id_cliente, $marca, $modelo, $anio, $placa);
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

    public function updateVehiculo($_post)
    {
        $conn = $this->dbConnect;
        // Obtener los datos del formulario
        $id_vehiculo = mysqli_real_escape_string($conn, $_post['id_vehiculo']);
        $id_cliente = mysqli_real_escape_string($conn, $_post['id_cliente']);
        $marca = mysqli_real_escape_string($conn, $_post['marca']);
        $modelo = mysqli_real_escape_string($conn, $_post['modelo']);
        $anio = mysqli_real_escape_string($conn, $_post['anio']);
        $placa = mysqli_real_escape_string($conn, $_post['placa']);
        $estado = mysqli_real_escape_string($conn, $_post['estado']);

        // Insertar vehiculo
        $sql = "UPDATE vehiculos set id_cliente = ?, marca = ?, modelo = ?, anio = ?, placa = ?, estado = ? where id_vehiculo = ?";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("ississi", $id_cliente, $marca, $modelo, $anio, $placa, $estado, $id_vehiculo);
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
};
//
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //
    if (isset($_GET["id_cliente"]) && $_GET["id_cliente"] >= 0 && isset($_GET["_acction"]) && $_GET["_acction"] == "DROPDOWN") {
        $Vehiculos = new Vehiculos();
        $vehiculos_result = $Vehiculos->getVehiculoPorCliente($_GET['id_cliente']);
        // Crear un arreglo con los datos
        $vehiculos = [];
        while ($row = $vehiculos_result->fetch_assoc()) {
            $vehiculos[] = $row;
        }
        // Devolver los datos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($vehiculos);
    }
};
//