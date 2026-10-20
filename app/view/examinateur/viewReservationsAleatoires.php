<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Réservations aléatoires ajoutées</h3>
        <div class="alert alert-success">
            <?php echo $count; ?> réservations ont été créées avec succès sur des trajets actifs.
        </div>
        
        <?php if ($count > 0): ?>
            <table class="table table-striped table-bordered mt-3">
                <thead>
                    <tr>
                        <th>ID Réservation</th>
                        <th>Passager</th>
                        <th>Trajet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($added_details as $detail): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detail['res_id']); ?></td>
                            <td><?php echo htmlspecialchars($detail['passager']); ?></td>
                            <td><?php echo htmlspecialchars($detail['trajet']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-danger">Aucun trajet actif ou aucun passager trouvé pour créer des réservations.</p>
        <?php endif; ?>
        
        <div class="mt-3">
            <a href="router.php?action=accueil" class="btn btn-primary">Retour à l'accueil</a>
            <a href="router.php?action=examinateurReservations" class="btn btn-outline-primary">Ajouter 10 de plus</a>
        </div>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
