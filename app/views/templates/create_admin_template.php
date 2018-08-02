
<div class="container">
<h3>Novi administrator</h3>
    <?php foreach($errors as $error): ?>
        <a class="red-text"><?= safe($error); ?></a><br>
    <?php endforeach; ?>

    <?php foreach($messages as $message): ?>
        <a class="green-text"><?= safe($message); ?></a><br>
    <?php endforeach; ?>

    <div class="row">
        <form class="col s12 white" method="post" action="?controller=createAdmin">
            <!-- username -->
            <div class="row">
                <div class="input-field col l6 s12">
                    <input id="username" name="username" type="text" class="validate" maxlength="40" value="">
                    <label for="username">Korisnicko ime</label>
                </div>
            </div>

            <!-- password -->
            <div class="row">
                <div class="input-field col l6 s12">
                    <input id="password" name="password1" type="password" class="validate">
                    <label for="password">Password</label>
                </div>
            </div>
            <!-- password repeat -->
            <div class="row">
                <div class="input-field col l6 s12">
                    <input id="password" name="password2" type="password" class="validate">
                    <label for="password">Password</label>
                </div>
            </div>

            <!-- organization pick -->
            <div class="row">
                <div class="input-field col l6 s12">
                    <select name="organization_id">
                        <option value="" disabled selected>Odaberite organizaciju</option>
                        <?php foreach($organizations as $organization): ?>
                            <option value="<?= safe($organization['id']); ?>"><?= safe($organization['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Odabrana organizacija</label>
                </div>
            </div>

            <a href="#" onclick="$(this).closest('form').submit()" class="waves-effect waves-light btn orange">Stvori</a>
        </form>
    </div>
</div>