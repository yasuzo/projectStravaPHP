<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Leaflet CSS -> used for maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
   integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
   crossorigin=""/>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <link rel="stylesheet" href="style.css">

    
    <title><?= safe($title); ?></title>


</head>
<body>
    
    <?php require_once '../app/views/partials/navbar_' . $authorizationLevel .'.php'; ?>

    <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

    <!-- Compiled and minified MaterializeCSS JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <!-- custom javascript -->
    <script type="text/javascript" src="basicScript.js"></script>
    
    <main>
        <?= $body ?>
    </main>

    <?php require_once '../app/views/partials/footer.php'; ?>

</body>
</html>