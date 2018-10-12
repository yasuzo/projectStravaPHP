<div class="container">
<h3>Nova organizacija</h3>
    <?php foreach($errors as $error): ?>
        <a class="red-text"><?= safe($error); ?></a><br>
    <?php endforeach; ?>

    <?php foreach($messages as $message): ?>
        <a class="green-text"><?= safe($message); ?></a><br>
    <?php endforeach; ?>

    <div class="row">
        <form id="create_organization" method="post" action="?controller=createOrganization">
                <div class="row">
                    <div class="input-field col l6 s12">
                        <input id="organization_name" name="organization_name" type="text" class="validate" maxlength="50">
                        <label for="organization_name">Naziv organizacije</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col l4 s12">
                        <input id="lat" name="lat" type="text" maxlength="15">
                        <label for="lat">Zemljopisna širina</label>
                    </div>
                    <div class="input-field col l4 s12">
                        <input id="lng" name="lng" type="text" maxlength="15">
                        <label for="lng">Zemljopisna dužina</label>
                    </div>
                </div>

            <a href="#" onclick="$('#create_organization').submit()" class="waves-effect waves-light btn button-safe">Stvori</a>
        </form>
    </div>
</div>