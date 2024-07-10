<?php
include_once 'db.php';

class Task {
    private $conn;

    public function __construct() {
        $database = new DB();
        $db = $database->getConnection();
        $this->conn = $db;
    }

    public function create($user_id, $title, $description) {
        $query = "INSERT INTO tasks (user_id, title, description) VALUES (:user_id, :title, :description)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function read($user_id) {
        $query = "SELECT * FROM tasks WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $user_id, $title, $description) {
        $query = "UPDATE tasks SET title = :title, description = :description WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete($id, $user_id) {
        $query = "DELETE FROM tasks WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
