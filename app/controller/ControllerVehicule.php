<?php
require_once '../model/ModelVehicule.php';
require_once '../model/ModelUtilisateur.php';

class ControllerVehicule {
    public static function readAll() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $vehicules = ModelVehicule::getAll();
        require '../view/vehicule/viewAll.php';
    }

    public static function create() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $utilisateurs = ModelUtilisateur::getAll();
        $conducteurs = array_filter($utilisateurs, function($u) { return $u->role === 'conducteur'; });
        require '../view/vehicule/viewInsert.php';
    }

    public static function created() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $marque = htmlspecialchars($_GET['marque']);
        $modele = htmlspecialchars($_GET['modele']);
        $annee = htmlspecialchars($_GET['annee']);
        $immatriculation = htmlspecialchars($_GET['immatriculation']);
        $proprietaire_id = htmlspecialchars($_GET['proprietaire_id']);
        $id = ModelVehicule::insert($marque, $modele, $annee, $immatriculation, $proprietaire_id);
        require '../view/vehicule/viewInserted.php';
    }

    public static function mesVehicules() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'conducteur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $vehicules = ModelVehicule::getByProprietaire($_SESSION['login_id']);
        require '../view/vehicule/viewMesVehicules.php';
    }
}
?>
