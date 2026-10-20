<?php
require_once 'Model.php';

class ModelReservation {
    private $id, $trajet_id, $passager_id;

    public function __construct($id = NULL, $trajet_id = NULL, $passager_id = NULL) {
        if (!is_null($id)) {
            $this->id = $id;
            $this->trajet_id = $trajet_id;
            $this->passager_id = $passager_id;
        }
    }

    public static function getByPassager($passager_id) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM reservation WHERE passager_id = :passager_id ORDER BY id DESC");
            $stmt->execute(['passager_id' => $passager_id]);
            return $stmt->fetchAll(PDO::FETCH_CLASS, "ModelReservation");
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getByTrajet($trajet_id) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM reservation WHERE trajet_id = :trajet_id");
            $stmt->execute(['trajet_id' => $trajet_id]);
            return $stmt->fetchAll(PDO::FETCH_CLASS, "ModelReservation");
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function countByTrajet($trajet_id) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservation WHERE trajet_id = :trajet_id");
            $stmt->execute(['trajet_id' => $trajet_id]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getOne($id) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM reservation WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'ModelReservation');
            return $stmt->fetch();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function insert($trajet_id, $passager_id) {
        try {
            $pdo = Model::getInstance();
            // Anti-double insertion check
            $stmtCheck = $pdo->prepare("SELECT id FROM reservation WHERE trajet_id = :trajet_id AND passager_id = :passager_id");
            $stmtCheck->execute(['trajet_id' => $trajet_id, 'passager_id' => $passager_id]);
            $existing = $stmtCheck->fetch();
            if ($existing) return $existing['id'];

            $stmtMax = $pdo->query("SELECT MAX(id) AS maxid FROM reservation");
            $row = $stmtMax->fetch(PDO::FETCH_ASSOC);
            $newid = ($row['maxid'] ?? 0) + 1;
            $stmt = $pdo->prepare("INSERT INTO reservation (id, trajet_id, passager_id) VALUES (:newid, :trajet_id, :passager_id)");
            $stmt->execute(['newid' => $newid, 'trajet_id' => $trajet_id, 'passager_id' => $passager_id]);
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
