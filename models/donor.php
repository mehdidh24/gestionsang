<?php
class Donor {
    private $conn;
    private $table = "donneurs";

    public $id_donneur;
    public $cin;
    public $nom;
    public $prenom;
    public $groupe_sanguin;
    public $rhesus;
    public $telephone;
    public $adresse;
    public $date_inscription;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET cin=:cin, nom=:nom, prenom=:prenom, groupe_sanguin=:groupe_sanguin, 
                      rhesus=:rhesus, telephone=:telephone, adresse=:adresse, date_inscription=NOW()";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":cin", $this->cin);
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":groupe_sanguin", $this->groupe_sanguin);
        $stmt->bindParam(":rhesus", $this->rhesus);
        $stmt->bindParam(":telephone", $this->telephone);
        $stmt->bindParam(":adresse", $this->adresse);

        return $stmt->execute();
    }

    public function read($search = '', $groupe_filter = '') {
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1";
        
        if (!empty($search)) {
            $query .= " AND (nom LIKE :search OR prenom LIKE :search OR cin LIKE :search)";
        }
        
        if (!empty($groupe_filter)) {
            $query .= " AND groupe_sanguin = :groupe_sanguin";
        }
        
        $query .= " ORDER BY nom, prenom";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $search_term = "%$search%";
            $stmt->bindParam(":search", $search_term);
        }
        
        if (!empty($groupe_filter)) {
            $stmt->bindParam(":groupe_sanguin", $groupe_filter);
        }
        
        $stmt->execute();
        return $stmt;
    }
}
?>