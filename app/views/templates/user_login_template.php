<div class="container z-depth-2 red login-card" style="text-align:center;">
    <h3 class="white-text">Ciklometar</h3>
    <p class="white-text">
        Prijavite se sa svojim Strava računom!
    </p>
    <a href="https://www.strava.com/oauth/authorize?client_id=20070&redirect_uri=<?= AUTH_CALLBACK_DOMAIN ?>/?controller=performStravaAuth&response_type=code&approval_prompt=auto&scope=view_private" id="connect-with-strava"><img src="connect_with_strava.png"></a>
    <div><small class="white-text">Prijavom prihvacate <a class="orange-text modal-trigger" href="#terms">uvjete koristenja</a>.</small></div>
    <div class="container z-depth-1 white darken-1 upute">
        <strong>**Upute za korištenje
        <ol>
            <li>Prilikom svakog odlaska prema svojoj organizaciji pokrenite <a class="orange-text" href="https://www.strava.com">Stravu.</a></li>
            <li>Snimajte aktivnost.</li>
            <li>Odaberite sport – bicikl.</li>
            <li>Pritisnite „Start“</li>
            <li>Zaustavite aktivnost po dolasku na odredište. Vaš rezultat će se automatski upisati na rang listu vaše organizacije.</li>
        </ol>
        </strong>
    </div>
</div>

 <!-- Modal Structure -->
<div id="terms" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Modal Header</h4>
        <div class="divider"></div>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ac dui mi. Quisque vitae pharetra odio. Ut auctor urna ut congue laoreet. Integer iaculis efficitur nunc nec pharetra. Morbi bibendum eu nisl sodales consectetur. Pellentesque eu lorem vel nisi auctor consequat nec sit amet felis. Praesent nisl dui, viverra vel sem vitae, commodo mollis odio.

Ut pretium neque diam, sit amet dignissim eros aliquam nec. Cras feugiat aliquet ligula, at maximus odio vulputate at. Aenean a massa fermentum dolor malesuada posuere. Vestibulum scelerisque justo at sollicitudin viverra. Donec in tellus vitae lectus ullamcorper maximus sed at metus. Suspendisse potenti. Duis ullamcorper, neque a commodo aliquam, mauris metus gravida mauris, a porttitor odio leo id orci. Duis nec commodo nisi, id suscipit turpis. Nulla eu suscipit mi, sit amet pretium felis. Nam consequat placerat justo, nec venenatis massa dapibus sed.

Suspendisse magna metus, placerat et tincidunAliquam nec risus varius nulla imperdiet volutpat id ac felis. Quisque id nulla ante. Nunc eleifend dolor vel tellus aliquam rhoncus. Aenean vel mollis metus. Phasellus eu efficitur massa. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec porta risus elit, et fermentum libero consequat pulvinar. Cras quam diam, eleifend nec viverra sit amet, fermentum nec dolor. Nunc posuere consequat pretium. In porta augue dapibus tortor vulputate, in luctus enim faucibus. Sed eu velit ut purus rhoncus blandit. Fusce sollicitudin risus venenatis nibh accumsan, ut ultrices eros dignissim. Nulla bibendum ante sed eros egestas, dignissim condimentum erat dignissim. Proin at ultrices risus. Fusce tempor bibendum nunc euismod sodales.</p>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn-flat">Zatvori</a>
    </div>
</div>