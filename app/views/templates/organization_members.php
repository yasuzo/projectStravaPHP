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
                <img src="<?= safe($user['picture_url']); ?>" alt="" class="circle">
                <span class="title"><b><?= safe($user['lastName']); ?>,&nbsp;<?= safe($user['firstName']); ?></b></span>
                <p>
                    <?= safe($user['username']); ?>
                </p>
                <form id="form_<?= safe($user['id']); ?>" method="post" action="?controller=changeUserState">
                    <?php if($user['banned'] == 0): ?>
                        <input type="hidden" name="user_id" value="<?= safe($user['id']); ?>">
                        <input type="hidden" name="allow" value="false">
                    <?php else: ?>
                        <input type="hidden" name="user_id" value="<?= safe($user['id']); ?>">
                        <input type="hidden" name="allow" value="true">
                    <?php endif; ?>
                </form>
                <?php if($user['banned'] == 0): ?>
                    <a id="<?= safe($user['id']); ?>" href="#" onclick="$('#form_<?= safe($user['id']); ?>').submit()" class="waves-effect waves-light btn red secondary-content">Zabrani</a>
                <?php else: ?>
                    <a id="<?= safe($user['id']); ?>" href="#" onclick="$('#form_<?= safe($user['id']); ?>').submit()" class="waves-effect waves-light btn green secondary-content">Dozvoli</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>