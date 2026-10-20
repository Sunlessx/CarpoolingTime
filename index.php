<?php
session_start();
$_SESSION['login_id'] = -1;
header('Location: app/router/router.php?action=accueil');
?>
