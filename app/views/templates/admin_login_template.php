<div class="container z-depth-2 red login-card" style="text-align:center;">
    <h3 class="white-text">Ciklometar</h3>
    <p class="white-text">Admin</p>
    <div class="container z-depth-2 white admin-login" style="text-align:center;padding:3em;">

    <?php foreach($errors as $err): ?>
        <p class="orange-text"><?= safe($err); ?></p>
    <?php endforeach; ?>


        <div class="row">
            <form class="col s12 white" method="post" action="?controller=adminLoginPage">
                <div class="row">
                    <div class="input-field col s12 m6 offset-m3">
                        <input id="username" name="username" type="text" class="validate">
                        <label for="username">Username</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 offset-m3">
                        <input id="password" name="password" type="password" class="validate">
                        <label for="password">Password</label>
                    </div>
                </div>
                <a href="#" onclick="$(this).closest('form').submit()" class="waves-effect waves-light btn orange">PRIJAVA</a>
            </form>
        </div>
    </div>
</div>