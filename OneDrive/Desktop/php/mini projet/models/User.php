<?php
class User {
    private $conn;
    private $table = "utilisateurs";

    public $id_utilisateur;
    public $nom;
    public $mot_de_passe;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        $query = "SELECT id_utilisateur, nom, mot_de_passe, role 
                  FROM " . $this->table . " 
                  WHERE nom = :nom 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nom", $this->nom);
        $stmt->execute();

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Vérifier le mot de passe
            if(password_verify($this->mot_de_passe, $row['mot_de_passe'])) {
                // Remplir les propriétés de l'utilisateur
                $this->id_utilisateur = $row['id_utilisateur'];
                $this->nom = $row['nom'];
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
    }
}
?>