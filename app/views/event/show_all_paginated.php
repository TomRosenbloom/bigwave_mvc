<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="container">
    
    
        <div class="paginationMessage">
            <?php echo $data['paginator']->get_message(); ?>
        </div>
        <div class="paginationLinks">
            <?php PaginationHelper::bootstrapify($data['paginator']->links_array()); ?>
        </div>
    
    <div class="" id="listContainer">
        <?php if(count($data['events_arr']) > 0){ ?>
        <div id="listItems">
            <?php foreach($data['events_arr'] as $event) { ?>
                <div class="" id="event_<?= $event['id']; ?>">
                    <h3><?php echo $event['title']; ?><small class="float-right"><?php echo $event['feed_name']; ?></small></h3>
                    <p>Date: <?php echo $event['event_date']; ?></p>
                    <p><?php echo $event['description']; ?></p>
                </div>
            <?php } ?>
        </div>
        <?php } else { ?>
            No events for <?= $data['feed_name']; ?>
        <?php } ?>
    </div>  
</div>


<?php require APP_ROOT . '/views/inc/footer.php'; ?>