<div class="container">
    <ul class="collection with-header">
        <li class="collection-header"><h4>Clanovi</h4></li>

        <?php if(empty($users) === true): ?>
            <li class="collection-item">
                <div class="center">Nema clanova.</div>
            </li>
        <?php endif; ?>

        <?php foreach($users as $user): ?>
            <li class="collection-item avatar">
                <!-- <img src="<?= ''// $users['picture_url']; ?>" alt="" class="circle"> -->
                <span class="title"><b><?= safe($users['lastName']); ?>,&nbsp;<?= safe($users['firstName']); ?></b></span>
                <p>
                    <?= safe($user['username']); ?>
                </p>
                <form id="form_<?= safe($users['id']); ?>" method="post" action="?controller=changeUserState">
                    <?php if($users['banned'] === 0): ?>
                        <input type="hidden" name="user_id" value="<?= safe($users['id']); ?>">
                        <input type="hidden" name="allow" value="false">
                    <?php else: ?>
                        <input type="hidden" name="user_id" value="<?= safe($users['id']); ?>">
                        <input type="hidden" name="allow" value="true">
                    <?php endif; ?>
                </form>
                <?php if($users['banned'] === 0): ?>
                    <a id="<?= safe($users['id']); ?>" href="#" onclick="$('#form_<?= safe($users['id']); ?>').submit()" class="waves-effect waves-light btn red secondary-content">Ukloni</a>
                <?php else: ?>
                    <a id="<?= safe($users['id']); ?>" href="#" onclick="$('#form_<?= safe($users['id']); ?>').submit()" class="waves-effect waves-light btn green secondary-content">Dozvoli</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>