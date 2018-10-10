
<div class="container">

    <h3 class="heading-text">Postavke</h3>
    <?php foreach($errors as $error): ?>
        <a class="red-text"><?= safe($error); ?></a><br>
    <?php endforeach; ?>

    <?php foreach($messages as $message): ?>
        <a class="green-text"><?= safe($message); ?></a><br>
    <?php endforeach; ?>

    <div>
        <form id="settings_form" class="white" method="post" action="?controller=userSettings">
            <div class="row">
                <div class="input-field col l4 s12">
                    <input id="firstName" name="firstName" type="text" class="validate" maxlength="25" value="<?= safe($user->firstName() ?? ''); ?>">
                    <label for="firstName">Ime</label>
                </div>
                <div class="input-field col l4 s12">
                    <input id="lastName" name="lastName" type="text" class="validate" maxlength="25" value="<?= safe($user->lastName() ?? ''); ?>">
                    <label for="lastName">Prezime</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col l6 s12">
                    <input id="username" name="username" type="text" class="validate" maxlength="40" value="<?= safe($user->username() ?? ''); ?>">
                    <label for="username">Korisničko ime</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <select name="organization_id">
                        <?php if($chosenOrganization !== false): ?>
                            <option value="<?= $chosenOrganization->id(); ?>" selected><?= safe($chosenOrganization->name()); ?></option>
                        <?php else: ?>
                            <option value="" disabled selected>Odaberite organizaciju</option>
                        <?php endif; ?>
                        <?php foreach($organizations as $org): ?>
                            <?php if($chosenOrganization === false || $chosenOrganization->id() !== $org['id']): ?>
                                <option value="<?= $org['id']; ?>"><?= safe($org['name']); ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <label>Odabrana organizacija</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <a href="#" onclick="$('#settings_form').submit()" class="waves-effect waves-light btn button-safe">SPREMI</a>
                </div>
            </div>
        </form>
    </div>
    <div class="divider"></div>
    <form id="delete_form" method="post" action="?controller=userDelete">
        <div class="row">
            <div class="input-field col s12">
                <a href="#" onclick="$('#delete_form').submit()" class="waves-effect waves-light btn button-danger">Izbriši račun</a>
            </div>
        </div>
    <form>
</div>