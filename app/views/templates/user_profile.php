
<div id="activities" class="container">
    <h3 class="heading-text">Moje aktivnosti</h3>

    <?php if(empty($activities) === true): ?>
    <div class="z-depth-1 blue-grey lighten-5 no-activities-found">
        <p><strong>Nema aktivnosti!</strong></p>
    </div>
    <?php endif; ?>

    <?php foreach($activities as $activity): ?>
    <div class="z-depth-1 blue-grey lighten-5 activity">
        <div class="activity-heading">
            <small><?= date('d.m.Y', strtotime($activity['ended_at'])); ?> @</small>
            <h5 class="subheading-text"><?= date('H:i', strtotime($activity['ended_at'])); ?>h</h5>
        </div>
        <div id="map_<?= safe($activity['id']); ?>" class="map"></div>
        <table>
            <tr>
                <td>Trajanje:<br><b class="subheading-text"><?= formatDuration($activity['duration']); ?></b></td>
                <td>Prijeđena udaljenost:<br><b class="subheading-text"><?= safe($activity['distance'] / 1000); ?>&nbsp;km</b></td>
            </tr>
        </table>
    </div>
    <?php endforeach; ?>

</div>

<!-- Leaflet skripta -> za mape -->
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
   integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
   crossorigin=""></script>

<script type="text/javascript" src="https://rawgit.com/jieter/Leaflet.encoded/master/Polyline.encoded.js"></script>   

<?php foreach($activities as $activity): ?>
        <script type="text/javascript">
            
            let encodedRoute_<?= safe($activity['id']); ?> = "<?= str_replace('\\', '\\\\', $activity['polyline']); ?>";
            let polyline_<?= safe($activity['id']); ?> = L.Polyline.fromEncoded(encodedRoute_<?= safe($activity['id']); ?>);
            let coordinates_<?= safe($activity['id']); ?> = polyline_<?= safe($activity['id']); ?>.getLatLngs();
            let map_<?= safe($activity['id']); ?> = L.map('map_<?= safe($activity['id']); ?>', {
                zoomControl: false,
                attributionControl: false,
                dragging: !L.Browser.mobile
            }).fitBounds(coordinates_<?= safe($activity['id']); ?>);

            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                }).addTo(map_<?= safe($activity['id']); ?>);

            polyline_<?= safe($activity['id']); ?> = L.polyline(
                coordinates_<?= safe($activity['id']); ?>,
                {
                    color: 'red',
                    weight: 3.5,
                    opacity: .8,
                    lineCap: 'butt'
                }
            );

            polyline_<?= safe($activity['id']); ?>.addTo(map_<?= safe($activity['id']); ?>);
            
            let end_latlng_<?= safe($activity['id']); ?> = [<?= $activity['latitude'] / 100000?>, <?= $activity['longitude'] / 100000 ?>];

            L.circleMarker(end_latlng_<?= safe($activity['id']); ?>, {radius: 6, fillOpacity: 1, fillColor: 'white', color: '#F00', weight: 1.5}).addTo(map_<?= safe($activity['id']); ?>);
            
            <?php if($organization !== null): ?>
                L.circle([<?= $organization->latitude() ?>, <?= $organization->longitude() ?>], 100).addTo(map_<?= safe($activity['id']); ?>);
                L.marker([<?= $organization->latitude() ?>, <?= $organization->longitude() ?>], {title: "Odabrana organizacija"}).addTo(map_<?= safe($activity['id']); ?>);
            <?php endif; ?>

        </script>
<?php endforeach; ?>