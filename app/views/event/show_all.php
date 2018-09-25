<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <h1>All events</h1>
        <?php foreach($data as $event) { ?>
            <h2><?php echo $event['title']; ?></h2>
            <p>Date: <?php echo $event['date']; ?></p>
            <p><?php echo $event['description']; ?></p>
        <?php } ?>
    </body>
</html>
