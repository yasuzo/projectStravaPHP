<div class="container z-depth-2 login-card">
    <h1><a href="?controller=index" class="brand-logo"><img src="logo.png" class="responsive-img" alt="Ciklometar logo"></a></h1>
    <p class="subheading-text">Admin</p>
    <div class="container z-depth-2 admin-login">

    <?php foreach($errors as $err): ?>
        <p class="accent"><?= safe($err); ?></p>
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
                <a href="#" onclick="$(this).closest('form').submit()" class="waves-effect waves-light btn button-safe">PRIJAVA</a>
            </form>
        </div>
    </div>
</div>