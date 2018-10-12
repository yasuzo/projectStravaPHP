<nav>
    <div class="nav-wrapper">
        <a href="?controller=index" class="brand-logo"><span class="main-text">Super admin</span></a>
        
        <a href="#" data-target="mobile" class="sidenav-trigger main-text"><i class="material-icons">menu</i></a>

        <ul class="right hide-on-med-and-down">
            <li><a href="?controller=index">Administratori</a></li>
            <li><a href="?controller=organizations">Organizacije</a></li>
            <li><a href="?controller=settings"><i class="material-icons">settings</i></a></li>
            <!-- Dropdown Trigger -->
            <li><a class="dropdown-trigger" href="#!" data-target="nav-dropdown"><i class="material-icons right">arrow_drop_down</i></a></li>
        </ul>

        <ul class="sidenav" id="mobile">
            <li><a href="?controller=index">Administratori</a></li>
            <li><a href="?controller=organizations">Organizacije</a></li>
            <li><a href="?controller=settings"><i class="material-icons">settings</i></a></li>
            <li><div class="divider"></div></li>
            <li><a href="#" onclick="$('#logout_form').submit()">Odjava</a></li>
            <form id="logout_form" action="?controller=logout" method="post"></form>
        </ul>
    
    </div>
</nav>

<ul id="nav-dropdown" class="dropdown-content">
    <li>
        <a href="#" onclick="$('#logout_dropdown_form').submit()">Odjava</a>
        <form id="logout_dropdown_form" action="?controller=logout" method="post">
        </form>
    </li>
</ul>