<?php
$login_id = $_SESSION['login_id'] ?? -1;
$login_role = $_SESSION['login_role'] ?? '';
$login_nom = $_SESSION['login_nom'] ?? '';
$login_prenom = $_SESSION['login_prenom'] ?? '';
$login_solde = $_SESSION['login_solde'] ?? 0;
// Mise à jour du solde en temps réel depuis la base de données
if ($login_id != -1) {
    $user_live = \ModelUtilisateur::getOne($login_id);
    if ($user_live) {
        $login_solde = $user_live->getSolde();
        $_SESSION['login_solde'] = $login_solde; // Optionnel : synchronise la session
    }
}
?>
<nav class="navbar navbar-expand-lg fixed-top" style="background-color: #FF8C42;">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold" href="router.php?action=accueil">🚗 Imad and Huy</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if ($login_id != -1): ?>
                    <li class="nav-item">
                        <span class="nav-link text-white"><strong><?php echo htmlspecialchars($login_nom . ' ' . $login_prenom); ?></strong></span>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link text-white" title="Solde Réel">💰 <?php echo number_format($login_solde, 2); ?>€</span>
                    </li>
                    <?php if ($login_role === 'passager'): 
                        $virtual = \ModelUtilisateur::getVirtualSolde($login_id);
                    ?>
                    <li class="nav-item">
                        <span class="nav-link text-white" title="Solde Virtuel (après réservations)">⏳ <?php echo number_format($virtual, 2); ?>€</span>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($login_id != -1 && $login_role === 'administrateur'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">⚙️ Admin</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="router.php?action=utilisateurReadAll">Liste des utilisateurs</a></li>
                            <li><a class="dropdown-item" href="router.php?action=utilisateurCreateConducteur">Ajouter un conducteur</a></li>
                            <li><a class="dropdown-item" href="router.php?action=utilisateurCreatePassager">Ajouter un passager</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="router.php?action=vehiculeReadAll">Liste des véhicules</a></li>
                            <li><a class="dropdown-item" href="router.php?action=vehiculeCreate">Ajouter un véhicule</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="router.php?action=villeReadAll">Liste des villes</a></li>
                            <li><a class="dropdown-item" href="router.php?action=villeCreate">Ajouter une ville</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($login_id != -1 && $login_role === 'conducteur'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">🚙 Conducteur</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="router.php?action=vehiculeMesVehicules">Mes véhicules</a></li>
                            <li><a class="dropdown-item" href="router.php?action=trajetMesTrajets">Mes trajets</a></li>
                            <li><a class="dropdown-item" href="router.php?action=trajetCreate">Créer un trajet</a></li>
                            <li><a class="dropdown-item" href="router.php?action=trajetPassagers">Voir passagers</a></li>
                            <li><a class="dropdown-item" href="router.php?action=trajetCloture">Clôturer un trajet</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($login_id != -1 && $login_role === 'passager'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">👤 Passager</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="router.php?action=reservationMesReservations">Mes réservations</a></li>
                            <li><a class="dropdown-item" href="router.php?action=reservationCreate">Réserver un trajet</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">💡 Innovations</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="router.php?action=innovations">Propositions</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">🔍 Examinateur</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="router.php?action=examinateurSuperglobales">Superglobales</a></li>
                        <li><a class="dropdown-item" href="router.php?action=examinateurReservations">Ajouter 10 réservations</a></li>
                    </ul>
                </li>

                <?php if ($login_id == -1): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="router.php?action=login">🔓 Se connecter</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="router.php?action=logout">🚪 Déconnexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
