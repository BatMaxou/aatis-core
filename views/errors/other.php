<?php $title = 'Erreur'; ?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once $_ENV['DOCUMENT_ROOT'].'/../views/includes/header.php'; ?>

<body>
    <?php require_once $_ENV['DOCUMENT_ROOT'].'/../views/includes/navbar.php'; ?>
    <h2>Erreur :</h2>
    <p><?php echo $error; ?></p>
</body>

</html>
