<?php
require_once 'Model.php';

class ModelNotation {
    private $id, $trajet_id, $passager_id, $conducteur_id, $note, $commentaire;

    public function __construct($id = NULL, $trajet_id = NULL, $passager_id = NULL, $conducteur_id = NULL, $note = NULL, $commentaire = NULL) {
        if (!is_null($id)) {
            $this->id = $id;
            $this->trajet_id = $trajet_id;
            $this->passager_id = $passager_id;
            $this->conducteur_id = $conducteur_id;
            $this->note = $note;
            $this->commentaire = $commentaire;
        }
    }

    public static function getByConducteur($conducteur_id) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM notation WHERE conducteur_id = :conducteur_id ORDER BY id DESC");
            $stmt->execute(['conducteur_id' => $conducteur_id]);
            return $stmt->fetchAll(PDO::FETCH_CLASS, "ModelNotation");
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getAverageByConducteur($conducteur_id) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT AVG(note) as moyenne FROM notation WHERE conducteur_id = :conducteur_id");
            $stmt->execute(['conducteur_id' => $conducteur_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['moyenne'] ? round($result['moyenne'], 2) : 0;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function insert($trajet_id, $passager_id, $conducteur_id, $note, $commentaire) {
        try {
            $pdo = Model::getInstance();
            $stmtMax = $pdo->query("SELECT MAX(id) AS maxid FROM notation");
            $row = $stmtMax->fetch(PDO::FETCH_ASSOC);
            $newid = $row['maxid'] + 1;
            $stmt = $pdo->prepare("INSERT INTO notation (id, trajet_id, passager_id, conducteur_id, note, commentaire) VALUES (:newid, :trajet_id, :passager_id, :conducteur_id, :note, :commentaire)");
            $stmt->execute(['newid' => $newid, 'trajet_id' => $trajet_id, 'passager_id' => $passager_id, 'conducteur_id' => $conducteur_id, 'note' => $note, 'commentaire' => $commentaire]);
            return $newid;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function __get($prop) {
        return $this->$prop;
    }
}
?>
