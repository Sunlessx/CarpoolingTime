<?php
require_once '../model/ModelUtilisateur.php';
require_once '../model/ModelVille.php';

class ControllerAccueil {
    public static function accueil() {
        require '../view/accueil/viewAccueil.php';
    }

    public static function innovations() {
        require '../view/innovations/viewInnovations.php';
    }

    public static function superglobales() {
        require '../view/examinateur/viewSuperglobales.php';
    }
}
?>
