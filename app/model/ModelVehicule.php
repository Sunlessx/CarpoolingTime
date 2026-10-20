<?php
require_once 'Model.php';

class ModelVehicule {
    private $id, $marque, $modele, $annee, $immatriculation, $proprietaire_id;

    public function __construct($id = NULL, $marque = NULL, $modele = NULL, $annee = NULL, $immatriculation = NULL, $proprietaire_id = NULL) {
        if (!is_null($id)) {
            $this->id = $id;
            $this->marque = $marque;
            $this->modele = $modele;
            $this->annee = $annee;
            $this->immatriculation = $immatriculation;
            $this->proprietaire_id = $proprietaire_id;
        }
    }

    public static function getAll() {
        try {
            $pdo = Model::getInstance();
            $query = $pdo->query("SELECT * FROM vehicule");
            return $query->fetchAll(PDO::FETCH_CLASS, "ModelVehicule");
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getByProprietaire($proprietaire_id) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM vehicule WHERE proprietaire_id = :proprietaire_id");
            $stmt->execute(['proprietaire_id' => $proprietaire_id]);
            return $stmt->fetchAll(PDO::FETCH_CLASS, "ModelVehicule");
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getOne($id) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM vehicule WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'ModelVehicule');
            return $stmt->fetch();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function insert($marque, $modele, $annee, $immatriculation, $proprietaire_id) {
        try {
            $pdo = Model::getInstance();
            $stmtMax = $pdo->query("SELECT MAX(id) AS maxid FROM vehicule");
            $row = $stmtMax->fetch(PDO::FETCH_ASSOC);
            $newid = $row['maxid'] + 1;
            $stmt = $pdo->prepare("INSERT INTO vehicule (id, marque, modele, annee, immatriculation, proprietaire_id) VALUES (:newid, :marque, :modele, :annee, :immatriculation, :proprietaire_id)");
            $stmt->execute(['newid' => $newid, 'marque' => $marque, 'modele' => $modele, 'annee' => $annee, 'immatriculation' => $immatriculation, 'proprietaire_id' => $proprietaire_id]);
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
