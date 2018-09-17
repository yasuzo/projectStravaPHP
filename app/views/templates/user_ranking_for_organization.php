<!-- CONTAINER -->
<div class="container">
    <!-- <h3 class="center-align red-text">RANG LISTA</h3> -->
    <h3>Rang lista</h3>
    <!-- TABS -->
    <div class="row">
    <div class="col s12">
        <ul class="tabs">
            <li class="tab col s3 offset-l3"><a class="active" href="#test1">PRIJEĐENI PUT</a></li>
            <li class="tab col s3"><a href="#test2">DOLASCI</a></li>
        </ul>
    </div>
    <!-- TAB No. 1 -->
    <div id="test1" class="col s12">
        <!-- TABLE No. 1 -->
        <table class="striped centered">
            <thead>
                <tr>
                    <th>Plasman</th>
                    <th>Korisničko ime</th>
                    <th>Kilometri</th>
                </tr>
            </thead>

            <tbody>
                <?php if(empty($usersByDistance) === true): ?>
                    <tr>
                        <td></td>
                        <td>Nema korisnika!</td>
                        <td></td>
                    </tr>
                <?php endif; ?>
                <?php $i = 1; ?>
                <?php foreach($usersByDistance as $user): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= safe($user['username']); ?></td>
                    <td><?= safe($user['distance'] / 1000); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- TAB No. 2 -->
    <div id="test2" class="col s12">
        <!-- TABLE No. 2 -->
        <table class="striped centered">
            <thead>
                <tr>
                    <th>Plasman</th>
                    <th>Korisničko ime</th>
                    <th>Dolasci</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($usersByCount) === true): ?>
                    <tr>
                        <td></td>
                        <td>Nema korisnika!</td>
                        <td></td>
                    </tr>
                <?php endif; ?>
                <?php $i = 1; ?>
                <?php foreach($usersByCount as $user): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= safe($user['username']); ?></td>
                    <td><?= safe($user['count']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</div>