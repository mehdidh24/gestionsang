<?php
class Don {
    private $conn;
    private $table = "dons";

    public $id_don;
    public $id_donneur;
    public $id_centre;
    public $date_don;
    public $statut;
    public $quantite;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET id_donneur=:id_donneur, id_centre=:id_centre, date_don=:date_don, 
                      statut='EN STOCK', quantite=:quantite";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_donneur", $this->id_donneur);
        $stmt->bindParam(":id_centre", $this->id_centre);
        $stmt->bindParam(":date_don", $this->date_don);
        $stmt->bindParam(":quantite", $this->quantite);

        return $stmt->execute();
    }

    public function readAll() {
        $query = "SELECT d.*, do.nom as nom_donneur, do.prenom, do.groupe_sanguin, do.rhesus, c.nom_centre 
                  FROM dons d 
                  JOIN donneurs do ON d.id_donneur = do.id_donneur 
                  JOIN centres_collecte c ON d.id_centre = c.id_centre 
                  ORDER BY d.date_don DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>