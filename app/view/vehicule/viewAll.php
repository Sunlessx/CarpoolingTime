<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Liste des véhicules</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Année</th>
                    <th>Immatriculation</th>
                    <th>Propriétaire</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicules as $v): 
                    $prop = \ModelUtilisateur::getOne($v->proprietaire_id);
                    $nom_prop = $prop ? htmlspecialchars($prop->nom . ' ' . $prop->prenom) : 'N/A';
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($v->id); ?></td>
                        <td><?php echo htmlspecialchars($v->marque); ?></td>
                        <td><?php echo htmlspecialchars($v->modele); ?></td>
                        <td><?php echo htmlspecialchars($v->annee); ?></td>
                        <td><?php echo htmlspecialchars($v->immatriculation); ?></td>
                        <td><?php echo $nom_prop; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
