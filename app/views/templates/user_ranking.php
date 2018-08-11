<!-- CONTAINER -->
<div class="container">
    <!-- <h3 class="center-align red-text">RANG LISTA</h3> -->
    <h3>Rang lista</h3>
    <?php if($chosenOrganization === false): ?>

    <p>Trenutno ne postoji niti jedna organizacija!</p>

    <?php else: ?>
    <!-- Orgaznization picker -->
    <ul id="organizationPicker" class="dropdown-content">
        <?php foreach($organizations as $org): ?>
            <li><a href="?controller=index&organization=<?= safe($org['id']); ?>" class="orange-text truncate"><?= safe($org['name']); ?></a></li>
        <?php endforeach; ?>
    </ul>
    <a class="btn dropdown-button orange" href="#!" data-activates="organizationPicker"><?= safe($chosenOrganization->name()); ?><i class="material-icons right">arrow_drop_down</i></a>

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
                    <th>Korisnicko ime</th>
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
                <?php foreach($usersByDistance as $usr): ?>
                <?php if($user->id() === $usr['id']): ?>
                <tr class="red lighten-3">
                    <td><?= $i++; ?></td>
                    <td><?= safe($usr['username']); ?></td>
                    <td><?= safe($usr['distance'] /1000); ?></td>
                </tr>
                <?php else: ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= safe($usr['username']); ?></td>
                    <td><?= safe($usr['distance'] / 1000); ?></td>
                </tr>
                <?php endif; ?>
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
                    <th>Korisnicko ime</th>
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
                <?php foreach($usersByCount as $usr): ?>
                <?php if($user->id() === $usr['id']): ?>
                <tr class="red lighten-3">
                    <td><?= $i++; ?></td>
                    <td><?= safe($usr['username']); ?></td>
                    <td><?= safe($usr['count']); ?></td>
                </tr>
                <?php else: ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= safe($usr['username']); ?></td>
                    <td><?= safe($usr['count']); ?></td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php endif; ?>

</div>