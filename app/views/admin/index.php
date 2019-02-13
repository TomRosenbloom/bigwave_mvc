<?php require APP_ROOT . '/views/inc/header.php'; ?>

    <h1>Admin</h1>

    <ul>
        <li><a href="<?= URL_ROOT; ?>/feed/refresh/1">Refresh the db from the LetsRide feed</a></li>
        <li><a href="<?= URL_ROOT; ?>/feed/refresh/BritishTriathlon">Refresh the db from the British Triathlon feed</a></li>
        <li><a href="<?= URL_ROOT; ?>/feed/refresh">Refresh the db from the activeNewham feed</a></li>
        <li><a href="<?= URL_ROOT; ?>/api/readAll">Create JSON feed from local data</a></li>
        <li><a href="<?= URL_ROOT; ?>/api/readOne/1">JSON data for one event from local data</a></li>
    </ul>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>
