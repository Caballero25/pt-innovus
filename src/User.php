<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // Validar datos
        if (empty($this->username) || empty($this->password)) {
            return false;
        }

        // Consulta
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password=:password";

        // Preparar sentencia
        $stmt = $this->conn->prepare($query);

        // Sanitizar y hashear
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        // Vincular parámetros
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);

        // Ejecutar
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function login() {
        // Validar datos
        if (empty($this->username) || empty($this->password)) {
            return false;
        }

        // Consulta
        $query = "SELECT id, password FROM " . $this->table_name . " WHERE username = :username";

        $stmt = $this->conn->prepare($query);

        // Vincular parámetros
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();

        // Obtener fila
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña
        if ($row && password_verify($this->password, $row['password'])) {
            $this->id = $row['id'];
            return true;
        }
        return false;
    }
}

?>

