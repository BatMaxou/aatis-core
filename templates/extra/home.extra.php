<!DOCTYPE html>
<html lang="fr">

<?php $renderer->render(
    $templatesFolderPath.'/includes/header.tpl.php',
    [
        'title' => $title,
    ]
); ?>

<body>
    <section id="home">
        <h1><?php echo $title; ?></h1>
    </section>
</body>

</html>
