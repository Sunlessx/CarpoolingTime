<?php
require_once 'Model.php';

class ModelUtilisateur {
    private $id, $nom, $prenom, $role, $login, $password, $solde;

    public function __construct($id = NULL, $nom = NULL, $prenom = NULL, $role = NULL, $login = NULL, $password = NULL, $solde = NULL) {
        if (!is_null($id)) {
            $this->id = $id;
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->role = $role;
            $this->login = $login;
            $this->password = $password;
            $this->solde = $solde;
        }
    }

    public static function getAll() {
        try {
            $pdo = Model::getInstance();
            $query = $pdo->query("SELECT * FROM utilisateur");
            return $query->fetchAll(PDO::FETCH_CLASS, "ModelUtilisateur");
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getOne($id) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'ModelUtilisateur');
            return $stmt->fetch();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getByLogin($login) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE login = :login");
            $stmt->execute(['login' => $login]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'ModelUtilisateur');
            return $stmt->fetch();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function insert($nom, $prenom, $role, $login, $password, $solde = 0) {
        try {
            $pdo = Model::getInstance();
            $stmtMax = $pdo->query("SELECT MAX(id) AS maxid FROM utilisateur");
            $row = $stmtMax->fetch(PDO::FETCH_ASSOC);
            $newid = $row['maxid'] + 1;
            $stmt = $pdo->prepare("INSERT INTO utilisateur (id, nom, prenom, role, login, password, solde) VALUES (:newid, :nom, :prenom, :role, :login, :password, :solde)");
            $stmt->execute(['newid' => $newid, 'nom' => $nom, 'prenom' => $prenom, 'role' => $role, 'login' => $login, 'password' => $password, 'solde' => $solde]);
            return $newid;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function updateSolde($id, $solde) {
        try {
            $pdo = Model::getInstance();
            $stmt = $pdo->prepare("UPDATE utilisateur SET solde = :solde WHERE id = :id");
            $res = $stmt->execute(['solde' => $solde, 'id' => $id]);
            return $res;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getVirtualSolde($id) {
        try {
            $user = self::getOne($id);
            if (!$user) return 0;
            
            $pdo = Model::getInstance();
            // Somme des prix des trajets actifs réservés par l'utilisateur
            $sql = "SELECT SUM(t.prix) FROM reservation r 
                    JOIN trajet t ON r.trajet_id = t.id 
                    WHERE r.passager_id = :id AND t.statut = 'actif'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $engagements = $stmt->fetchColumn();
            
            return $user->solde - ($engagements ?: 0);
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function __get($prop) {
        return $this->$prop;
    }
    
    public function getSolde() {
        return $this->solde;
    }
    
    public function getId() {
        return $this->id;
    }
}
?>
