<div class="row">
    <div class="col s12">
        <ul class="tabs">

            <li class="tab col s12"><a class="active" href="#activities">MOJE AKTIVNOSTI</a></li>
        </ul>
    </div>
    <div id="activities" class="col s12">

        <?php if(empty($activities) === true): ?>
        <div class="container z-depth-4 blue-grey lighten-5 no-activities-found" style="padding-top: 1em; padding-bottom: 1em; text-align:center; margin-top: 1em; margin-bottom: 1em;">
            <strong>Nema aktivnosti!</strong>
        </div>
        <?php endif; ?>

        <?php foreach($activities as $activity): ?>
        <div class="container z-depth-4 blue-grey lighten-5 activity" style="margin-top: 1em;">
            <small>&nbsp;<?= date('d.m.Y', $activity['ended_at']); ?> @</small>
            <h5><?= date('H:i', $activity['ended_at']); ?>h</h5>
            <hr>
            <div class="row">
                <div class="col s12" style="margin-top: -0.5em">
                    <div id="map_<?= safe($activity['id']); ?>" style="height: 20em; self-align: center; background-color:rgba(10, 211, 151, 0.179); width: 100%"></div>
                </div>
                <div class="col s12" style="margin-top: -0.5em;">
                    <hr>
                </div>
                <div class="col s12">
                    <table class="activity-card-table">
                        <tr>
                            <td>Trajanje:<br><strong><b><?= (new DateInterval('PT' . $activity['duration'] . 'S'))->format('%h:%i:%s'); ?></b></strong></td>
                            <td style="border-left: 1px solid rgba(121, 121, 121, 0.789);">Prijeđena udaljenost:<br><strong><b><?= safe($activity['distance'] / 100); ?>&nbsp;km</b></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<!-- Leaflet skripta -> za mape -->
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
   integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
   crossorigin=""></script>

<script type="text/javascript" src="https://rawgit.com/jieter/Leaflet.encoded/master/Polyline.encoded.js"></script>   

<?php foreach($activities as $activity): ?>
        <script type="text/javascript">
            
            let encodedRoute_<?= safe($activity['id']); ?> = "<?= $activity['polyline']; ?>";
            let polyline_<?= safe($activity['id']); ?> = L.Polyline.fromEncoded(encodedRoute_<?= safe($activity['id']); ?>);
            let coordinates_<?= safe($activity['id']); ?> = polyline_<?= safe($activity['id']); ?>.getLatLngs();
            let map_<?= safe($activity['id']); ?> = L.map('map_<?= safe($activity['id']); ?>', {
                zoomControl: false,
                attributionControl: false,
                dragging: !L.Browser.mobile
            }).fitBounds(coordinates_<?= safe($activity['id']); ?>);

            L.tileLayer(
                'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                }).addTo(map_<?= safe($activity['id']); ?>);
            L.polyline(
                coordinates_<?= safe($activity['id']); ?>,
                {
                    color: 'red',
                    weight: 5,
                    opacity: .8,
                    lineJoin: 'round'
                }
            ).addTo(map_<?= safe($activity['id']); ?>);
        </script>
<?php endforeach; ?>