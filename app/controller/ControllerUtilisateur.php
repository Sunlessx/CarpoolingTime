<?php
require_once '../model/ModelUtilisateur.php';

class ControllerUtilisateur {
    public static function login() {
        require '../view/utilisateur/viewLogin.php';
    }

    public static function logined() {
        $login = htmlspecialchars($_GET['login'] ?? '');
        $password = htmlspecialchars($_GET['password'] ?? '');

        $user = ModelUtilisateur::getByLogin($login);
        if ($user && $user->password === $password) {
            $_SESSION['login_id'] = $user->id;
            $_SESSION['login_role'] = $user->role;
            $_SESSION['login_nom'] = $user->nom;
            $_SESSION['login_prenom'] = $user->prenom;
            $_SESSION['login_solde'] = $user->solde;
            header('Location: router.php?action=accueil');
        } else {
            echo '<h3>Identifiants incorrects</h3>';
            echo '<a href="router.php?action=login">Retour</a>';
        }
    }

    public static function logout() {
        $_SESSION['login_id'] = -1;
        session_destroy();
        header('Location: router.php?action=accueil');
    }

    public static function readAll() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $utilisateurs = ModelUtilisateur::getAll();
        require '../view/utilisateur/viewAll.php';
    }

    public static function createConducteur() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        require '../view/utilisateur/viewInsertConducteur.php';
    }

    public static function createdConducteur() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $nom = htmlspecialchars($_GET['nom']);
        $prenom = htmlspecialchars($_GET['prenom']);
        $login = htmlspecialchars($_GET['login']);
        $password = htmlspecialchars($_GET['password']);
        $solde = htmlspecialchars($_GET['solde'] ?? 100);
        $id = ModelUtilisateur::insert($nom, $prenom, 'conducteur', $login, $password, $solde);
        require '../view/utilisateur/viewInserted.php';
    }

    public static function createPassager() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        require '../view/utilisateur/viewInsertPassager.php';
    }

    public static function createdPassager() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $nom = htmlspecialchars($_GET['nom']);
        $prenom = htmlspecialchars($_GET['prenom']);
        $login = htmlspecialchars($_GET['login']);
        $password = htmlspecialchars($_GET['password']);
        $solde = htmlspecialchars($_GET['solde'] ?? 100);
        $id = ModelUtilisateur::insert($nom, $prenom, 'passager', $login, $password, $solde);
        require '../view/utilisateur/viewInserted.php';
    }
}
?>
