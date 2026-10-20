<?php
require_once 'Model.php';

class ModelTrajet {
    private $id, $ville_depart, $ville_arrivee, $conducteur_id, $vehicule_id, $prix, $date_depart, $heure_depart, $statut;

    public function __construct($id = NULL, $ville_depart = NULL, $ville_arrivee = NULL, $conducteur_id = NULL, $vehicule_id = NULL, $prix = NULL, $date_depart = NULL, $heure_depart = NULL, $statut = NULL) {
        if (!is_null($id)) {
            $this->id = $id;
            $this->ville_depart = $ville_depart;
            $this->ville_arrivee = $ville_arrivee;
            $this->conducteur_id = $conducteur_id;
            $this->vehicule_id = $vehicule_id;
            $this->prix = $prix;
            $this->date_depart = $date_depart;
            $this->heure_depart = $heure_depart;
            $this->statut = $statut;
        }
    }

    public static function getByConducteur($conducteur_id) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM trajet WHERE conducteur_id = :conducteur_id ORDER BY date_depart DESC");
            $stmt->execute(['conducteur_id' => $conducteur_id]);
            return $stmt->fetchAll(PDO::FETCH_CLASS, "ModelTrajet");
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getActifs() {
        try {
            $pdo = Model::getInstance();
            $query = $pdo->query("SELECT * FROM trajet WHERE statut = 'actif' ORDER BY date_depart DESC");
            return $query->fetchAll(PDO::FETCH_CLASS, "ModelTrajet");
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getOne($id) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM trajet WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'ModelTrajet');
            return $stmt->fetch();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function insert($ville_depart, $ville_arrivee, $conducteur_id, $vehicule_id, $prix, $date_depart, $heure_depart) {
        try {
            $pdo = Model::getInstance();
            // Anti-double insertion check
            $stmtCheck = $pdo->prepare("SELECT id FROM trajet WHERE ville_depart = :vd AND ville_arrivee = :va AND conducteur_id = :cid AND date_depart = :dd AND heure_depart = :hd");
            $stmtCheck->execute(['vd' => $ville_depart, 'va' => $ville_arrivee, 'cid' => $conducteur_id, 'dd' => $date_depart, 'hd' => $heure_depart]);
            $existing = $stmtCheck->fetch();
            if ($existing) return $existing['id'];

            $stmtMax = $pdo->query("SELECT MAX(id) AS maxid FROM trajet");
            $row = $stmtMax->fetch(PDO::FETCH_ASSOC);
            $newid = ($row['maxid'] ?? 0) + 1;
            $stmt = $pdo->prepare("INSERT INTO trajet (id, ville_depart, ville_arrivee, conducteur_id, vehicule_id, prix, date_depart, heure_depart, statut) VALUES (:newid, :ville_depart, :ville_arrivee, :conducteur_id, :vehicule_id, :prix, :date_depart, :heure_depart, 'actif')");
            $stmt->execute(['newid' => $newid, 'ville_depart' => $ville_depart, 'ville_arrivee' => $ville_arrivee, 'conducteur_id' => $conducteur_id, 'vehicule_id' => $vehicule_id, 'prix' => $prix, 'date_depart' => $date_depart, 'heure_depart' => $heure_depart]);
            return $newid;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function updateStatut($id, $statut) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("UPDATE trajet SET statut = :statut WHERE id = :id");
            $stmt->execute(['statut' => $statut, 'id' => $id]);
            return true;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function __get($prop) {
        return $this->$prop;
    }
}
?>
