<?php $title = 'Error '.$errorTag; ?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once $_ENV['DOCUMENT_ROOT'].'/../views/includes/header.php'; ?>

<body>
    <h1>Error :</h1>
    <p><?php echo $errorDescription; ?></p>
</body>

</html>
