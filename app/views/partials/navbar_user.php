
<nav>
    <div class="nav-wrapper">
        <a href="?controller=index" class="brand-logo"><img src="logo.png" class="navbar-logo" alt="Ciklometar logo"></a>
        
        <a href="#" data-target="mobile" class="sidenav-trigger main-text"><i class="material-icons">menu</i></a>

        <ul class="right hide-on-med-and-down">
            <li><a href="?controller=index">Rang lista</a></li>
            <li><a href="?controller=profile">Profil</a></li>
            <li><a href="?controller=userSettings"><i class="material-icons">settings</i></a></li>
            <li><a href="#guide" class="tooltipped modal-trigger" data-position="bottom" data-tooltip="Upute"><i class="material-icons">help</i></a></li>
            <!-- Dropdown Trigger -->
            <li><a href="#!" class="dropdown-trigger" data-target="nav-dropdown"><i class="material-icons right">arrow_drop_down</i></a></li>
        </ul>

        <ul class="right hide-on-large-only">
            <li><a href="#guide" class="modal-trigger"><i class="material-icons">help</i></a></li>
        </ul>

        <ul class="sidenav" id="mobile">
            <li><a href="?controller=index">Rang lista</a></li>
            <li><a href="?controller=profile">Profil</a></li>
            <li><a href="?controller=userSettings"><i class="material-icons">settings</i></a></li>
            <li><div class="divider"></div></li>
            <li><a href="#" onclick="$('#logout_form').submit()">Odjava</a></li>
            <form id="logout_form" action="?controller=logout" method="post"></form>
        </ul>
    
    </div>
</nav>

<!-- Dropdown content -->
<ul id="nav-dropdown" class="dropdown-content">
    <li>
        <a href="#" onclick="$('#logout_dropdown_form').submit()">Odjava</a>
        <form id="logout_dropdown_form" action="?controller=logout" method="post">
        </form>
    </li>
</ul>

<!-- Modal Structure -->
<div id="guide" class="modal">
    <div class="modal-content">
        <h4 class="heading-text">Upute</h4>
        <div class="divider"></div>
        <ol>
            <li>Prilikom svakog odlaska prema svojoj organizaciji pokrenite Stravu.</li>
            <li>Snimajte aktivnost.</li>
            <li>Odaberite sport – bicikl.</li>
            <li>Pritisnite <b class="accent">Start</b></li>
            <li>Zaustavite aktivnost po dolasku na odredište. Vaš rezultat će se automatski upisati na rang listu vaše organizacije.</li>
        </ol>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-purple btn-flat">Zatvori</a>
    </div>
</div>