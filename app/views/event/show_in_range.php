<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <style media="screen">
            /* make list scrollable */
            #listItems {
                height: 600px
                overflow: scroll;
                padding: 10px;
                border: solid grey 1px;
                border-radius: 2px;
                box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; // copied from google maps callout
            }
        </style>

        <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
    </head>
    <body>

        <div class="container">
            <h1>Events in range</h1>

            <div class="" id="formContainer">
                <h2>Range</h2>
                <div class="row">
                    <div class="w-100" id="form">
                        <form class="" action="" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-8" id="postcode-input">
                                    <label for="postcode">Postcode: </label>
                                    <input class="form-control" type="text" name="postcode" value="<?= $data['post_vars']['postcode']; ?>" placeholder="enter a valid UK postcode">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-8" id="range-input">
                                    <label for="range">Range: </label>
                                    <input class="form-control" type="text" name="range" value="<?= $data['post_vars']['range']; ?>" placeholder="enter a range in km">
                                </div>
                            </div>
                            <input class="btn btn-primary" type="submit" value="Submit">
                        </form>
                    </div>
                </div>
            </div>

            <div class="" id="mapContainer">
                <h2>Map</h2>
                <div id="map" style="height:400px;">
                </div>
            </div>

            <div class="" id="listContainer">
                <h2>List</h2>
                <div id="listItems" style="height: 600px; overflow: scroll;">
                    <?php foreach($data['events_arr'] as $event) { ?>
                        <div class="" id="event_<?= $event['id']; ?>">
                            <h3><?php echo $event['title']; ?></h3>
                            <p>Date: <?php echo $event['event_date']; ?></p>
                            <p><?php echo $event['description']; ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>


        <script type='text/javascript'>
            <?php echo "var events=" . $data['events_json'] . "\n"; ?>

            var markers = [];

            function initMap() {
                var map = new google.maps.Map(document.getElementById('map'), {
                  center: new google.maps.LatLng(54, 0),
                  zoom: 5
                });
                var infoWindow = new google.maps.InfoWindow;
                var oms = new OverlappingMarkerSpiderfier(map, {
                  markersWontMove: true,
                  markersWontHide: true,
                  basicFormatEvents: true
                });

                for(var key in events){
                    var lat = events[key].latitude;
                    var lng = events[key].longitude;
                    var title = events[key].title;
                    var description = events[key].description;
                    var id = events[key].id;

                    var contentString = '<div id="content">'+
                        '<h1 id="firstHeading" class="firstHeading">' + title + '</h1>'+
                        '<div id="bodyContent">'+
                        '<p>' + description + '</p>'+
                        '</div>'+
                        '</div>';
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(lat,lng),
                        name: title,
                        map: map,
                        id: id,
                        content: contentString // nb there is nothing special in the name 'content'
                    });
                    marker.addListener('spider_click', function() {
                      infoWindow.setContent(this.content);
                      infoWindow.open(map, this);

                      // scroll the list to clicked item
                      var parentDiv = $('#listItems');
                      var innerListItem = $("#event_"+this.id);
                      parentDiv.animate({
                            scrollTop:
                                parentDiv.scrollTop() + (innerListItem.position().top - parentDiv.position().top)
                        }, 2000);

                    });
                    markers.push(marker);
                    oms.addMarker(marker); // use spiderfier to separate markers on same location
                }
                var markerCluster = new MarkerClusterer(map, markers,
                     {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
                     minClusterZoom = 14;
                markerCluster.setMaxZoom(minClusterZoom);
            }
            </script>

        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js"></script>
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZTujpv59V9O6S4CymkaMkKzCg6hA2a1I&callback=initMap">
        </script>

    </body>
</html>
