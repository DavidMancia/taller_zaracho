<?php
class Clientes
{
    private $dbConnect = null;

    public function __construct()
    {
        // Conexión a la base de datos
        $host = "localhost";
        $user = "tallerZaracho";
        $password = "postgres";
        $dbname = "taller_zaracho";

        $conn = new mysqli($host, $user, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $this->dbConnect = $conn;
    }

    public function getClientes()
    {
        $conn = $this->dbConnect;
        // Función para obtener clientes
        $sql = "SELECT * FROM clientes";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getCliente($id_cliente)
    {
        $conn = $this->dbConnect;
        // Función para obtener clientes
        $sql = "SELECT * FROM clientes where id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function insertCliente($_post)
    {
        $conn = $this->dbConnect;
        $cedula = mysqli_real_escape_string($conn, $_post['cedula']);
        $nombre_apellido = mysqli_real_escape_string($conn, $_post['nombre_apellido']);
        $telefono = mysqli_real_escape_string($conn, $_post['telefono']);
        $direccion = mysqli_real_escape_string($conn, $_post['direccion']);
        // Insertar cliente
        $sql = "INSERT INTO clientes (cedula, nombre_apellido, telefono, direccion) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("ssss", $cedula, $nombre_apellido, $telefono, $direccion);
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

    public function updateCliente($_post)
    {
        $conn = $this->dbConnect;
        // Obtener los datos del formulario
        $id_cliente = mysqli_real_escape_string($conn, $_post['id_cliente']);
        $cedula = mysqli_real_escape_string($conn, $_post['cedula']);
        $nombre_apellido = mysqli_real_escape_string($conn, $_post['nombre_apellido']);
        $telefono = mysqli_real_escape_string($conn, $_post['telefono']);
        $direccion = mysqli_real_escape_string($conn, $_post['direccion']);
        $estado = mysqli_real_escape_string($conn, $_post['estado']);

        // Insertar cliente
        $sql = "UPDATE clientes set cedula = ?, nombre_apellido = ?, telefono = ?, direccion = ?, estado = ? where id_cliente = ?";
        $stmt = $conn->prepare($sql);
        if (false === $stmt) {
            die('prepare() failed: ' . $conn->error);
        }
        $result = $stmt->bind_param("sssssi", $cedula, $nombre_apellido, $telefono, $direccion, $estado, $id_cliente);
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
