<?php
require_once '../model/ModelVille.php';

class ControllerVille {
    public static function readAll() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $villes = ModelVille::getAll();
        require '../view/ville/viewAll.php';
    }

    public static function create() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        require '../view/ville/viewInsert.php';
    }

    public static function created() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $nom = htmlspecialchars($_GET['nom']);
        $id = ModelVille::insert($nom);
        require '../view/ville/viewInserted.php';
    }
}
?>
