<?php require APP_ROOT . '/views/inc/header.php'; ?>

    <h1>Event details</h1>
    <h2><?= $data['title']; ?></h2>
    <p>Date: <?= $data['event_date']; ?></p>
    <p><?= $data['description']; ?></p>

    <?php require APP_ROOT . '/views/inc/footer.php'; ?>
