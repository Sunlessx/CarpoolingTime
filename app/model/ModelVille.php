<?php
require_once 'Model.php';

class ModelVille {
    private $id, $nom;

    public function __construct($id = NULL, $nom = NULL) {
        if (!is_null($id)) {
            $this->id = $id;
            $this->nom = $nom;
        }
    }

    public static function getAll() {
        try {
            $pdo = Model::getInstance();
            $query = $pdo->query("SELECT * FROM ville ORDER BY nom");
            return $query->fetchAll(PDO::FETCH_CLASS, "ModelVille");
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getOne($id) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM ville WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'ModelVille');
            return $stmt->fetch();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function insert($nom) {
        try {
            $pdo = Model::getInstance();
            $stmtMax = $pdo->query("SELECT MAX(id) AS maxid FROM ville");
            $row = $stmtMax->fetch(PDO::FETCH_ASSOC);
            $newid = $row['maxid'] + 1;
            $stmt = $pdo->prepare("INSERT INTO ville (id, nom) VALUES (:newid, :nom)");
            $stmt->execute(['newid' => $newid, 'nom' => $nom]);
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
