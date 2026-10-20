<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Liste des utilisateurs</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Rôle</th>
                    <th>Login</th>
                    <th>Solde</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u->id); ?></td>
                        <td><?php echo htmlspecialchars($u->nom); ?></td>
                        <td><?php echo htmlspecialchars($u->prenom); ?></td>
                        <td><?php echo htmlspecialchars($u->role); ?></td>
                        <td><?php echo htmlspecialchars($u->login); ?></td>
                        <td><?php echo number_format($u->solde, 2); ?>€</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
