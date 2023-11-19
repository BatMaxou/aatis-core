<?php $title = 'Hello ' . $name . ' !'; ?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once $_ENV['DOCUMENT_ROOT'] . '/../views/includes/header.php'; ?>

<body>
    <section id="hello">
        <h1><?php echo $title; ?></h1>
    </section>
</body>

</html>
