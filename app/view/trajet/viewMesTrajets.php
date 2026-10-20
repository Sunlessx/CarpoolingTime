<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Mes trajets</h3>
        <?php if (count($trajets) > 0): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Prix</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trajets as $t): 
                        $ville_dep = \ModelVille::getOne($t->ville_depart);
                        $ville_arr = \ModelVille::getOne($t->ville_arrivee);
                        $dep = $ville_dep ? htmlspecialchars($ville_dep->nom) : 'N/A';
                        $arr = $ville_arr ? htmlspecialchars($ville_arr->nom) : 'N/A';
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($t->id); ?></td>
                            <td><?php echo $dep; ?></td>
                            <td><?php echo $arr; ?></td>
                            <td><?php echo htmlspecialchars($t->date_depart); ?></td>
                            <td><?php echo htmlspecialchars($t->heure_depart); ?></td>
                            <td><?php echo number_format($t->prix, 2); ?>€</td>
                            <td><?php echo htmlspecialchars($t->statut); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Vous n'avez pas de trajets.</p>
        <?php endif; ?>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
