<div id="admin-settings" class="container">
<h3>Postavke</h3>
    <?php foreach($messages as $message): ?>
        <a class="green-text"><?= safe($message); ?></a><br>
    <?php endforeach; ?>

    <div class="row">
        <ul class="collection with-header">
            <li class="collection-header"><h5 class="subheading-text">Postavke korisničkog imena</h5></li>
            
            <li class="collection-item">

                <?php foreach($usernameErrors as $error): ?>
                    <a class="red-text"><?= safe($error); ?></a><br>
                <?php endforeach; ?>

                <form id="update_username" class="col s12" method="post" action="?controller=settings&update=username">
                    <!-- username -->
                    <div class="row">
                        <div class="input-field col l6 s12">
                            <input id="username" name="username" type="text" class="validate" maxlength="40" value="<?= safe($username); ?>">
                            <label for="username">Korisničko ime</label>
                        </div>
                    </div>


                    <!-- password -->
                    <div class="row">
                        <div class="input-field col l6 s12">
                            <input id="password" name="password" type="password" class="validate">
                            <label for="password">Unesite lozinku za potvrdu</label>
                        </div>
                    </div>

                    <a href="#" onclick="$('#update_username').submit()" class="waves-effect waves-light btn button-safe">Promijeni</a>
                </form>
            </li>
        </ul>
    </div>

    <div class="row">

        <ul class="collection with-header">
            <li class="collection-header"><h5 class="subheading-text">Promjena lozinke</h5></li>
            
            <li class="collection-item">

                <?php foreach($passwordErrors as $error): ?>
                    <a class="red-text"><?= safe($error); ?></a><br>
                <?php endforeach; ?>
                
                <form id="update_password" class="col s12" method="post" action="?controller=settings&update=password">
                    <!-- password -->
                    <div class="row">
                        <div class="input-field col l6 s12">
                            <input id="password1" name="newPassword1" type="password" class="validate">
                            <label for="password1">Nova lozinka</label>
                        </div>
                    </div>
                    <!-- password repeat -->
                    <div class="row">
                        <div class="input-field col l6 s12">
                            <input id="password2" name="newPassword2" type="password" class="validate">
                            <label for="password2">Ponovite novu lozinku</label>
                        </div>
                    </div>

                    <!-- password -->
                    <div class="row">
                        <div class="input-field col l6 s12">
                            <input id="password3" name="password" type="password" class="validate">
                            <label for="password3">Unesite staru lozinku za potvrdu</label>
                        </div>
                    </div>

                    <a href="#" onclick="$('#update_password').submit()" class="waves-effect waves-light btn button-safe">Promijeni</a>
                </form>
            </li>
        </ul>
    </div>
</div>