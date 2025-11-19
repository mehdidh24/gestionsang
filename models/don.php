<?php
class don{
    private $conn;
    private $table_name = "dons";   
    public $id_don;
    public $id_donneur; 
    public $statut;
    public $id_centre;  
    public function __construct($db) {
        $this->conn = $db;
    }
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (id_donneur, statut, id_centre) VALUES (:id_donneur, :statut, :id_centre)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_donneur', $this->id_donneur);
        $stmt->bindParam(':statut', $this->statut);
        $stmt->bindParam(':id_centre', $this->id_centre);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
}
