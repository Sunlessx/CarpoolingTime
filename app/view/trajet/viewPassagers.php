<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Passagers du trajet</h3>
        <?php if (count($reservations) > 0): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Réservation</th>
                        <th>Passager</th>
                        <th>Login</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $res): 
                        $passager = \ModelUtilisateur::getOne($res->passager_id);
                        $nom = $passager ? htmlspecialchars($passager->nom . ' ' . $passager->prenom) : 'N/A';
                        $login = $passager ? htmlspecialchars($passager->login) : 'N/A';
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($res->id); ?></td>
                            <td><?php echo $nom; ?></td>
                            <td><?php echo $login; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun passager pour ce trajet.</p>
        <?php endif; ?>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
