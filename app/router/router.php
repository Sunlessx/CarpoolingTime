<?php
session_start();
// si on arrive directement ici sans passer par index.php, on est "non connecté"
$_SESSION['login_id'] = $_SESSION['login_id'] ?? -1;

require_once '../controller/ControllerAccueil.php';
require_once '../controller/ControllerUtilisateur.php';
require_once '../controller/ControllerVehicule.php';
require_once '../controller/ControllerVille.php';
require_once '../controller/ControllerTrajet.php';
require_once '../controller/ControllerReservation.php';

$args = $_GET ?? [];
$action = $args['action'] ?? 'accueil';

switch ($action) {
    case 'accueil':
        ControllerAccueil::accueil();
        break;
    case 'login':
        ControllerUtilisateur::login();
        break;
    case 'logined':
        ControllerUtilisateur::logined();
        break;
    case 'logout':
        ControllerUtilisateur::logout();
        break;
    case 'utilisateurReadAll':
        ControllerUtilisateur::readAll();
        break;
    case 'utilisateurCreateConducteur':
        ControllerUtilisateur::createConducteur();
        break;
    case 'utilisateurCreatedConducteur':
        ControllerUtilisateur::createdConducteur();
        break;
    case 'utilisateurCreatePassager':
        ControllerUtilisateur::createPassager();
        break;
    case 'utilisateurCreatedPassager':
        ControllerUtilisateur::createdPassager();
        break;
    case 'vehiculeReadAll':
        ControllerVehicule::readAll();
        break;
    case 'vehiculeCreate':
        ControllerVehicule::create();
        break;
    case 'vehiculeCreated':
        ControllerVehicule::created();
        break;
    case 'vehiculeMesVehicules':
        ControllerVehicule::mesVehicules();
        break;
    case 'villeReadAll':
        ControllerVille::readAll();
        break;
    case 'villeCreate':
        ControllerVille::create();
        break;
    case 'villeCreated':
        ControllerVille::created();
        break;
    case 'trajetMesTrajets':
        ControllerTrajet::mesTrajets();
        break;
    case 'trajetCreate':
        ControllerTrajet::create();
        break;
    case 'trajetCreated':
        ControllerTrajet::created();
        break;
    case 'trajetPassagers':
        ControllerTrajet::passagers();
        break;
    case 'trajetCloture':
        ControllerTrajet::cloture();
        break;
    case 'reservationMesReservations':
        ControllerReservation::mesReservations();
        break;
    case 'reservationCreate':
        ControllerReservation::create();
        break;
    case 'reservationCreated':
        ControllerReservation::created();
        break;
    case 'examinateurReservations':
        ControllerReservation::reservationsAleatoires();
        break;
    case 'innovations':
        ControllerAccueil::innovations();
        break;
    case 'examinateurSuperglobales':
        ControllerAccueil::superglobales();
        break;
    default:
        echo "<h2>Erreur : action '$action' non reconnue</h2>";
        break;
}
?>
