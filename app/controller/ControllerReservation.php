<?php
require_once '../model/ModelReservation.php';
require_once '../model/ModelTrajet.php';
require_once '../model/ModelVille.php';
require_once '../model/ModelUtilisateur.php';

class ControllerReservation {
    public static function mesReservations() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'passager') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $reservations = ModelReservation::getByPassager($_SESSION['login_id']);
        require '../view/reservation/viewMesReservations.php';
    }

    public static function create() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'passager') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $trajets = ModelTrajet::getActifs();
        require '../view/reservation/viewInsert.php';
    }

    public static function created() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'passager') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $trajet_id = htmlspecialchars($_GET['trajet_id']);
        $trajet = ModelTrajet::getOne($trajet_id);
        
        $virtual_solde = ModelUtilisateur::getVirtualSolde($_SESSION['login_id']);
        
        if ($virtual_solde < $trajet->prix) {
            echo '<div class="container mt-5"><div class="alert alert-danger"><h3>Solde insuffisant</h3>';
            echo '<p>Votre solde virtuel ('.number_format($virtual_solde, 2).'€) ne permet pas de réserver ce trajet ('.number_format($trajet->prix, 2).'€).</p>';
            echo '<a href="router.php?action=reservationCreate" class="btn btn-primary">Retour</a></div></div>';
            return;
        }
        
        $id = ModelReservation::insert($trajet_id, $_SESSION['login_id']);
        require '../view/reservation/viewInserted.php';
    }

    public static function reservationsAleatoires() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $trajets = ModelTrajet::getActifs();
        $utilisateurs = ModelUtilisateur::getAll();
        $passagers = array_values(array_filter($utilisateurs, function($u) { return $u->role === 'passager'; }));
        
        $added_details = [];
        $count = 0;
        
        if (count($trajets) > 0 && count($passagers) > 0) {
            for ($i = 0; $i < 10; $i++) {
                $trajet = $trajets[array_rand($trajets)];
                $passager = $passagers[array_rand($passagers)];
                
                $id = ModelReservation::insert($trajet->id, $passager->id);
                
                $ville_dep = ModelVille::getOne($trajet->ville_depart);
                $ville_arr = ModelVille::getOne($trajet->ville_arrivee);
                
                $added_details[] = [
                    'res_id' => $id,
                    'passager' => $passager->nom . " " . $passager->prenom,
                    'trajet' => ($ville_dep ? $ville_dep->nom : "?") . " -> " . ($ville_arr ? $ville_arr->nom : "?")
                ];
                $count++;
            }
        }
        
        require '../view/examinateur/viewReservationsAleatoires.php';
    }
}
?>
