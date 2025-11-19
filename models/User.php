<?php
class User {
    private $conn;
    private $table_name = "utilisateurs";

    public $id;
    public $nom;
    public $mot_de_passe;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        $query = "SELECT id_utilisateur, nom, mot_de_passe, role FROM " . $this->table_name . " WHERE nom = :nom LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($this->mot_de_passe, $row['mot_de_passe'])) {
            $this->id = $row['id_utilisateur'];
            $this->role = $row['role'];
            return true;
        }
        return false;
    }
}