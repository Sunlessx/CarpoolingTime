<?php
require_once '../model/ModelTrajet.php';
require_once '../model/ModelVehicule.php';
require_once '../model/ModelVille.php';
require_once '../model/ModelReservation.php';
require_once '../model/ModelUtilisateur.php';

class ControllerTrajet {
    public static function mesTrajets() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'conducteur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $trajets = ModelTrajet::getByConducteur($_SESSION['login_id']);
        require '../view/trajet/viewMesTrajets.php';
    }

    public static function create() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'conducteur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $villes = ModelVille::getAll();
        $vehicules = ModelVehicule::getByProprietaire($_SESSION['login_id']);
        require '../view/trajet/viewInsert.php';
    }

    public static function created() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'conducteur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $ville_depart = htmlspecialchars($_GET['ville_depart']);
        $ville_arrivee = htmlspecialchars($_GET['ville_arrivee']);
        $vehicule_id = htmlspecialchars($_GET['vehicule_id']);
        $prix = htmlspecialchars($_GET['prix']);
        $date_depart = htmlspecialchars($_GET['date_depart']);
        $heure_depart = htmlspecialchars($_GET['heure_depart']);
        $id = ModelTrajet::insert($ville_depart, $ville_arrivee, $_SESSION['login_id'], $vehicule_id, $prix, $date_depart, $heure_depart);
        require '../view/trajet/viewInserted.php';
    }

    public static function passagers() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'conducteur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $trajet_id = htmlspecialchars($_GET['trajet_id'] ?? '');
        if (!$trajet_id) {
            $trajets = ModelTrajet::getByConducteur($_SESSION['login_id']);
            require '../view/trajet/viewSelectTrajetPassagers.php';
            return;
        }
        $trajet = ModelTrajet::getOne($trajet_id);
        $reservations = ModelReservation::getByTrajet($trajet_id);
        require '../view/trajet/viewPassagers.php';
    }

    public static function cloture() {
        if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'conducteur') {
            echo '<h3>Accès refusé</h3>';
            return;
        }
        $trajet_id = htmlspecialchars($_GET['trajet_id'] ?? '');
        if (!$trajet_id) {
            $trajets = ModelTrajet::getByConducteur($_SESSION['login_id']);
            require '../view/trajet/viewSelectTrajetCloture.php';
            return;
        }
        $trajet = ModelTrajet::getOne($trajet_id);
        if ($trajet->statut === 'passif') {
            echo '<h3>Ce trajet est déjà clôturé</h3>';
            return;
        }

        // tout dans une transaction : soit tous les paiements passent, soit aucun
        $pdo = Model::getInstance();
        try {
            $pdo->beginTransaction();

            // 1. Marquer le trajet comme passif
            ModelTrajet::updateStatut($trajet_id, 'passif');

            // 2. Récupérer les réservations
            $reservations = ModelReservation::getByTrajet($trajet_id);
            $count_paid = 0;

            foreach ($reservations as $res) {
                $passager = ModelUtilisateur::getOne($res->passager_id);
                if ($passager) {
                    // Déduire l'argent du passager
                    $current_solde = $passager->getSolde();
                    $prix_trajet = $trajet->prix;
                    $nouveau_solde_passager = $current_solde - $prix_trajet;
                    ModelUtilisateur::updateSolde($res->passager_id, $nouveau_solde_passager);
                    $count_paid++;
                }
            }

            // 3. Créditer le conducteur
            $conducteur = ModelUtilisateur::getOne($_SESSION['login_id']);
            if ($conducteur) {
                $current_solde_cond = $conducteur->getSolde();
                $total_paye = $count_paid * $trajet->prix;
                $nouveau_solde_conducteur = $current_solde_cond + $total_paye;
                ModelUtilisateur::updateSolde($_SESSION['login_id'], $nouveau_solde_conducteur);
            }

            $pdo->commit();
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            die("Erreur lors de la clôture : " . $e->getMessage());
        }

        // Mettre à jour la session du conducteur connecté (seulement si tout est validé)
        if (isset($nouveau_solde_conducteur)) {
            $_SESSION['login_solde'] = $nouveau_solde_conducteur;
        }

        require '../view/trajet/viewClotured.php';
    }
}
?>
