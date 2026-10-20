<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Mes réservations</h3>
        <?php if (count($reservations) > 0): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Conducteur</th>
                        <th>Véhicule</th>
                        <th>Immatriculation</th>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Prix</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $res): 
                        $trajet = \ModelTrajet::getOne($res->trajet_id);
                        $conducteur = \ModelUtilisateur::getOne($trajet->conducteur_id);
                        $vehicule = \ModelVehicule::getOne($trajet->vehicule_id);
                        $ville_dep = \ModelVille::getOne($trajet->ville_depart);
                        $ville_arr = \ModelVille::getOne($trajet->ville_arrivee);
                        
                        $nom_cond = $conducteur ? htmlspecialchars($conducteur->nom . ' ' . $conducteur->prenom) : 'N/A';
                        $marque_modele = $vehicule ? htmlspecialchars($vehicule->marque . ' ' . $vehicule->modele) : 'N/A';
                        $immat = $vehicule ? htmlspecialchars($vehicule->immatriculation) : 'N/A';
                        $dep = $ville_dep ? htmlspecialchars($ville_dep->nom) : 'N/A';
                        $arr = $ville_arr ? htmlspecialchars($ville_arr->nom) : 'N/A';
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($res->id); ?></td>
                            <td><?php echo $nom_cond; ?></td>
                            <td><?php echo $marque_modele; ?></td>
                            <td><?php echo $immat; ?></td>
                            <td><?php echo $dep; ?></td>
                            <td><?php echo $arr; ?></td>
                            <td><?php echo htmlspecialchars($trajet->date_depart); ?></td>
                            <td><?php echo htmlspecialchars($trajet->heure_depart); ?></td>
                            <td><?php echo number_format($trajet->prix, 2); ?>€</td>
                            <td><?php echo htmlspecialchars($trajet->statut); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Vous n'avez pas de réservations.</p>
        <?php endif; ?>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
