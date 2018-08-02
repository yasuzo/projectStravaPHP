<navbar>

    <ul id="navbarDropdown" class="dropdown-content">
	    <li><a href="?controller=logout">Odjava</a></li>
    </ul>
  
    <nav>
        <div class="nav-wrapper red">
            <a href="?controller=index" class="brand-logo">Super admin</a>
            
            <a href="#" data-activates="mobile" class="button-collapse"><i class="material-icons">menu</i></a>

            <ul class="right hide-on-med-and-down">
                <li><a href="?controller=index">Administratori</a></li>
                <li><a href="?controller=organizations">Organizacije</a></li>
                <li><a href="?controller=settings"><i class="material-icons">settings</i></a></li>
                <!-- Dropdown Trigger -->
                <li><a class="dropdown-button" href="#!" data-activates="navbarDropdown"><i class="material-icons right">arrow_drop_down</i></a></li>
            </ul>

            <ul class="side-nav" id="mobile">
                <li><a href="?controller=index">Administratori</a></li>
                <li><a href="?controller=organizations">Organizacije</a></li>
                <li><a href="?controller=settings"><i class="material-icons">settings</i></a></li>
                <li><div class="divider"></div></li>
                <li><a href="?controller=logout">Odjava</a></li>
            </ul>
        
        </div>
    </nav>
</navbar>