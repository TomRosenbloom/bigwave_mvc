<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <h1>All events</h1>
        <h2>Map</h2>

        <div id="map" style="height:400px; width:600px">
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


                for(var key in events){
                    var lat = events[key].latitude;
                    var lng = events[key].longitude;
                    var title = events[key].title;
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(lat,lng),
                        name:title,
                        map: map
                    });
                    marker.addListener('click', function() {
                      infoWindow.setContent(this.name);
                      infoWindow.open(map, this);
                    });
                    markers.push(marker);
                    // nb there is a more elegant way of doing this. This kind of thing:
                    // var markers = locations.map(function(location, i) {
                    //      return new google.maps.Marker({
                    //      position: location,
                    //      label: labels[i % labels.length]
                    // });
        });
                }
                var markerCluster = new MarkerClusterer(map, markers,
                     {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
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
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZTujpv59V9O6S4CymkaMkKzCg6hA2a1I&callback=initMap">
        </script>

    </body>
</html>
