<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="jumbotron jumbotron-flud text-center">
    <div class="container">
        <h1 class="display-3"><?= $data['title']; ?></h1>
        <p class="lead"><?= $data['description']; ?></p>
    </div>

</div>



<ul>
    <li><a href="<?= URL_ROOT; ?>/admin/">Admin</a></li>
    <li><a href="<?= URL_ROOT; ?>/event/">Events</a></li>
</ul>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>
