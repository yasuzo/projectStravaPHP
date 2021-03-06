<div class="container">
<h3><?= safe($organization->name()); ?></h3>
    <?php foreach($errors as $error): ?>
        <a class="red-text"><?= safe($error); ?></a><br>
    <?php endforeach; ?>

    <?php foreach($messages as $message): ?>
        <a class="green-text"><?= safe($message); ?></a><br>
    <?php endforeach; ?>

    <div>
        <form id="update_organization" method="post" action="?controller=organizationSettings">
                <div class="row">
                    <div class="input-field col l6 s12">
                        <input id="organization_name" name="organization_name" type="text" class="validate" maxlength="50" value="<?= safe($organization->name()); ?>">
                        <label for="organization_name">Naziv organizacije</label>
                    </div>
                </div>
                <div class="row valign-wrapper">
                    <div class="input-field col l4 s5">
                        <input id="lat" name="lat" type="text" maxlength="15" value="<?= safe($organization->latitude()); ?>">
                        <label for="lat">Zemljopisna širina</label>
                    </div>
                    <div class="input-field col l4 s5">
                        <input id="lng" name="lng" type="text" maxlength="15" value="<?= safe($organization->longitude()); ?>">
                        <label for="lng">Zemljopisna dužina</label>
                    </div>
                    <div class="col s2">
                        <a href="#map_modal" class="btn button-safe waves-effect waves-light modal-trigger white-text"><i class="material-icons">map</i></a>
                    </div>
                </div>

            <a href="#" onclick="$('#update_organization').submit()" class="waves-effect waves-light btn button-safe">Spremi</a>
        </form>
    </div>
</div>

<!-- Modal Structure -->
<div id="map_modal" class="modal organization-placement">
    <div class="modal-content">
        <div id="map"></div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-purple btn-flat">Zatvori</a>
    </div>
</div>

<!-- Leaflet skripta -> za mape -->
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
   integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
   crossorigin=""></script>

<script type="text/javascript">

    $(document).ready(() => {
        $('.modal').modal({
            onOpenEnd: function(){
                map.invalidateSize();
            }
        });
    });

    var coordinates = [<?= $organization->latitude() ?>, <?= $organization->longitude() ?>];

    var map = L.map('map', 
        {
            zoomControl: false,
            center: coordinates,
            zoom: 18,
            attributionControl: false,
            dragging: !L.Browser.mobile
        }
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
        {
            maxZoom: 18,
        }
    ).addTo(map);

    var circle = L.circle(coordinates, 100);
    circle.addTo(map);
    var marker = L.marker(coordinates, {title: "Organizacija", draggable: true});
    marker.addTo(map);

    var latitude = document.getElementById("lat");
    var longitude = document.getElementById("lng");

    marker.on('drag', function(ev){
        latitude.value = ev.latlng.lat;
        longitude.value = ev.latlng.lng;
        circle.setLatLng(ev.latlng);
    });

</script>

