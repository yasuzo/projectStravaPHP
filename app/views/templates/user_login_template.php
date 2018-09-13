<div class="container z-depth-2 red login-card" style="text-align:center;">
    <h3><a class="white-text" href="/">Ciklometar</a></h3>
    <p class="white-text">
        Prijavite se sa svojim Strava računom!
    </p>
    <a href="https://www.strava.com/oauth/authorize?client_id=20070&redirect_uri=<?= AUTH_CALLBACK_DOMAIN ?>/?controller=performStravaAuth&response_type=code&approval_prompt=auto&scope=view_private" id="connect-with-strava"><img src="connect_with_strava.png"></a>
    <div><small class="white-text">Prijavom protvrđujete da ste pročitali i razumjeli informacije navedene <a class="orange-text text-lighten-3 modal-trigger" href="#terms">ovdje</a>.</small></div>
    <div class="container z-depth-1 white darken-1 upute">
        <strong>**Upute za korištenje
        <ol>
            <li>Prilikom svakog odlaska prema svojoj organizaciji pokrenite <a class="orange-text" href="https://www.strava.com">Stravu.</a></li>
            <li>Snimajte aktivnost.</li>
            <li>Odaberite sport – bicikl.</li>
            <li>Pritisnite <b class="orange-text">Start</b></li>
            <li>Zaustavite aktivnost po dolasku na odredište. Vaš rezultat će se automatski upisati na rang listu vaše organizacije.</li>
        </ol>
        </strong>
    </div>
</div>

 <!-- Modal Structure -->
<div id="terms" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Informacije o Ciklometru</h4>
        <div class="divider"></div>
        <h6><b>Opće informacije</b></h6>
        <p>Web servis Ciklometar (u daljnjem tekstu samo Ciklometar) radi na način da prikuplja podatke o kretanju korisnika preko mobilne aplikacije Strava.
        Prikupljanje podataka moguće je tek nakon što je korisnik to odobrio u samoj Strava aplikaciji.
        Ciklometar prikuplja podatke o kretanju isključivo kada se odredište poklapa sa odredištem organizacije pri kojoj je korisnik registriran.
        Ciklometar objavljuje listu korisnika sa brojem njihovih dolazaka do odredišta i brojem prijeđenih kilometara.</p>
        <h6><b>Prijava i korisnički račun</b></h6>
        <p>U postupku prijave korisnik se prijavljuje preko aplikacije Strava sa korisničkim podacima od aplikacije Strava.</p>
        <h6><b>Privatnost</b></h6>
        <p>Ciklometar u najvećoj mogućoj mjeri štiti privatnost svojih korisnika.
        Ciklometar će dozvoliti uvid o broju dolazaka te broju prijeđenih kilometara svim svojim korisnicima.
        Objavljena lista, osim samih brojčanih podataka, sadrži i korisnička imena korisnika koje nije moguće jednoznačno pridijeliti određenoj osobi.
        Ciklometar se obvezuje da ime i prezime osobe koje se traži pri registraciji neće objavljivati javno već ih prikuplja isključivo za potrebe organizacije pri kojoj je korisnik registriran.
        Imajući u vidu da je internet javno dostupna podatkovna mreža te da postoje osobe koje posjeduju takva stručna znanja i vještine pomoću kojih, usprkos sofisticiranim mjerama zaštite koje se primjenjuju, koristeći se internetom, kakvom drugom mrežom, uređajem ili resursom mogu izvršiti različite neovlaštene i protupravne radnje/djela u odnosu na internet stranicu, internet resurse objavljene na internet stranici, Ciklometar ne može jamčiti korisnicima sigurnost i funkcionalnost internet resursa, kao ni sigurnost i funkcionalnost internet stranice. Stoga, svaki korisnik koristi internet stranicu na svoju vlastitu odgovornost.</p>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn-flat">Zatvori</a>
    </div>
</div>