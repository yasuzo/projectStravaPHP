<!-- CONTAINER -->
<div class="container">
    <!-- <h3 class="center-align red-text">RANG LISTA</h3> -->
    <h3 class="heading-text center">RANG LISTA</h3>
    <?php if($chosenOrganization === false): ?>

    <p>Trenutno ne postoji niti jedna organizacija!</p>

    <?php else: ?>
    <!-- Orgaznization picker -->
    <ul id="organization-pick" class="dropdown-content content-text">
        <?php foreach($organizations as $org): ?>
            <li><a href="?controller=index&organization=<?= safe($org['id']); ?>" class="truncate"><?= safe($org['name']); ?></a></li>
        <?php endforeach; ?>
    </ul>
    <a class="btn dropdown-trigger button-safe" href="#!" data-target="organization-pick"><?= safe($chosenOrganization->name()); ?><i class="material-icons right">arrow_drop_down</i></a>

    <!-- TABS -->
    <div class="row">
        <div class="col s12">
            <ul class="tabs transparent">
                <li class="tab col s3 offset-l3"><a class="active" href="#distance-ranking">PRIJEĐENI PUT</a></li>
                <li class="tab col s3"><a href="#count-ranking">DOLASCI</a></li>
            </ul>
        </div>
        <!-- TAB No. 1 -->
        <div id="distance-ranking" class="col s12">
            <!-- TABLE No. 1 -->
            <table class="striped centered ranking-table">
                <thead>
                    <tr class="subheading-text">
                        <th>Plasman</th>
                        <th>Korisničko ime</th>
                        <th>Kilometri</th>
                    </tr>
                </thead>

                <tbody class="content-text">
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
                    <tr class="highlight-color">
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
        <div id="count-ranking" class="col s12">
            <!-- TABLE No. 2 -->
            <table class="striped centered ranking-table">
                <thead>
                    <tr class="subheading-text">
                        <th>Plasman</th>
                        <th>Korisničko ime</th>
                        <th>Dolasci</th>
                    </tr>
                </thead>
                <tbody class="content-text">
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
                    <tr class="highlight-color">
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