<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <h1>All events</h1>
        <h2>Map</h2>

        <div id="map" style="height:400px;">
        </div>



        <?php //echo "<pre>"; var_dump($data); echo "</pre>"; ?>

        <?php $events = json_encode($data); ?>

        <script type='text/javascript'>
            <?php echo "var events=$events;\n"; ?>

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
                        content: contentString // nb there is nothing special in the name 'content'
                    });
                    marker.addListener('spider_click', function() {
                      infoWindow.setContent(this.content);
                      infoWindow.open(map, this);
                    });
                    markers.push(marker);
                    oms.addMarker(marker); // use spiderfier to separate markers on same location

                    // nb there is a more elegant way of doing this. This kind of thing:
                    // var markers = locations.map(function(location, i) {
                    //      return new google.maps.Marker({
                    //      position: location, // depending on you data, you might not need to separately specify lat & long
                    //      label: labels[i % labels.length] // this example used a separate array of labels
                    // });

                }
                var markerCluster = new MarkerClusterer(map, markers,
                     {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
                     minClusterZoom = 14;
                markerCluster.setMaxZoom(minClusterZoom); // to uncluster markers at same location
                                                          // works to a point but doesn't show all the markers
                                                          // For it to work properly I think I need 'Spiderfier'
            }
            </script>

        <h2>List</h2>
        <?php foreach($data as $event) { ?>
            <h3><?php echo $event['title']; ?></h3>
            <p>Date: <?php echo $event['event_date']; ?></p>
            <p><?php echo $event['description']; ?></p>
        <?php } ?>

        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js"></script>
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZTujpv59V9O6S4CymkaMkKzCg6hA2a1I&callback=initMap">
        </script>

    </body>
</html>
