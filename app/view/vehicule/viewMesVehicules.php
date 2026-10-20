<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Mes véhicules</h3>
        <?php if (count($vehicules) > 0): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Année</th>
                        <th>Immatriculation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicules as $v): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($v->id); ?></td>
                            <td><?php echo htmlspecialchars($v->marque); ?></td>
                            <td><?php echo htmlspecialchars($v->modele); ?></td>
                            <td><?php echo htmlspecialchars($v->annee); ?></td>
                            <td><?php echo htmlspecialchars($v->immatriculation); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Vous n'avez pas de véhicules enregistrés.</p>
        <?php endif; ?>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
