<?php require APP_ROOT . '/views/inc/header.php'; ?>

    <h1>Admin</h1>

    <ul>
        <li><a href="<?= URL_ROOT; ?>/feed/refresh">Refresh db from feed</a></li>
        <li><a href="<?= URL_ROOT; ?>/feed/readAll">Create JSON feed from local data</a></li>
        <li><a href="<?= URL_ROOT; ?>/feed/readOne/1">JSON data for one event from local data</a></li>
    </ul>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>
