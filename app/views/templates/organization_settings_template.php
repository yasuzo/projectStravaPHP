<div class="container">
<h3><?= safe($organization->name()); ?></h3>
    <?php foreach($errors as $error): ?>
        <a class="red-text"><?= safe($error); ?></a><br>
    <?php endforeach; ?>

    <?php foreach($messages as $message): ?>
        <a class="green-text"><?= safe($message); ?></a><br>
    <?php endforeach; ?>

    <div class="row">
        <form id="update_organization" method="post" action="?controller=organizationSettings">
                <div class="row">
                    <div class="input-field col l6 s12">
                        <input id="organization_name" name="organization_name" type="text" class="validate" maxlength="50" value="<?= safe($organization->name()); ?>">
                        <label for="organization_name">Naziv organizacije</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col l4 s12">
                        <input id="lat" name="lat" type="text" maxlength="15" value="<?= safe($organization->latitude()); ?>">
                        <label for="lat">Zemljopisna širina</label>
                    </div>
                    <div class="input-field col l4 s12">
                        <input id="lng" name="lng" type="text" maxlength="15" value="<?= safe($organization->longitude()); ?>">
                        <label for="lng">Zemljopisna dužina</label>
                    </div>
                </div>

            <a href="#" onclick="$('#update_organization').submit()" class="waves-effect waves-light btn orange">Spremi</a>
        </form>
    </div>
</div>