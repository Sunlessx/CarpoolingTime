<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Innovations du Projet</h3>
        
        <div class="alert alert-success">
            <strong>Système Intégré :</strong> Les innovations ci-dessous sont pleinement fonctionnelles.
        </div>

        <div class="card mb-3 border-warning">
            <div class="card-header text-white" style="background-color: #FF8C42;">
                <h5>💎 Innovation 1 : Système de Solde Virtuel & Intégrité Financière</h5>
            </div>
            <div class="card-body">
                <p><strong>Concept :</strong> Pour éviter les découverts et assurer le paiement des conducteurs, nous avons implémenté un double système de solde.</p>
                <ul>
                    <li><strong>Solde Réel (💰) :</strong> L'argent actuellement disponible sur le compte de l'utilisateur.</li>
                    <li><strong>Solde Virtuel (⏳) :</strong> Le solde restant après avoir soustrait le coût de toutes les réservations en cours (trajets actifs).</li>
                </ul>
                <p><strong>Fonctionnement :</strong></p>
                <ul>
                    <li><strong>Garde-fou à la réservation :</strong> Un passager ne peut pas réserver un trajet si son <em>Solde Virtuel</em> est inférieur au prix du trajet.</li>
                    <li><strong>Calcul dynamique :</strong> Le solde virtuel est recalculé en temps réel à chaque affichage du menu.</li>
                </ul>
            </div>
        </div>

        <div class="card mb-3 border-info">
            <div class="card-header text-white bg-info">
                <h5>🚀 Amélioration MVC Proposée : Le Repository Pattern</h5>
            </div>
            <div class="card-body">
                <p><strong>Description :</strong> Une amélioration majeure de l'architecture actuelle serait l'introduction du <strong>Repository Pattern</strong> entre les Contrôleurs et les Modèles.</p>
                <p><strong>Pourquoi ?</strong></p>
                <ul>
                    <li><strong>Abstraction de la source de données :</strong> Le contrôleur ne sait pas si les données viennent d'une base SQL, d'un fichier JSON ou d'une API externe.</li>
                    <li><strong>Tests Unitaires facilités :</strong> On peut facilement créer des "Mock Repositories" pour tester les contrôleurs sans base de données réelle.</li>
                    <li><strong>Réduction de la duplication :</strong> Centralise les requêtes complexes qui pourraient être utilisées par plusieurs modèles ou contrôleurs.</li>
                </ul>
                <p><strong>Exemple de structure :</strong> <code>UtilisateurRepository->findWithActiveReservations($id)</code></p>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header text-white" style="background-color: #FF8C42;">
                <h5>🛡️ Contrôle d'Accès et Cohérence des Données</h5>
            </div>
            <div class="card-body">
                <p><strong>Description :</strong> Filtrage strict des rôles dans l'interface d'administration et de réservation.</p>
                <ul>
                    <li>Seuls les 'conducteurs' peuvent posséder des véhicules.</li>
                    <li>Transactions SQL atomiques (Begin/Commit) pour garantir que le transfert d'argent est complet ou annulé en cas d'erreur.</li>
                </ul>
            </div>
        </div>

        <div class="mt-4">
            <a href="router.php?action=accueil" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
