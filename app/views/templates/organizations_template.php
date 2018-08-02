<div class="row" style="width: 100%">
    <div class="container">
        <ul class="collection with-header">

            <li class="collection-header"><h4>Organizacije</h4></li>

            <?php foreach($organizations as $organization): ?>
            <li class="collection-item">
                <form id="organization-delete-<?= safe($organization['id']); ?>" method="post" action="?controller=deleteOrganization">
                    <input type="hidden" name="organization_id" value="<?= safe($organization['id']); ?>">
                </form>
                <div><?= safe($organization['name']) ?>
                    <a href="#" onclick="$('#organization-delete-<?= safe($organization['id']); ?>').submit();" class="secondary-content"><i class="material-icons">delete</i></a>
                </div>
            </li>
        <?php endforeach; ?>

            <li class="collection-item">
                <div class="center"><a href="?controller=createOrganization" class="btn orange modal-trigger"><i class="material-icons">add</i></a></div>
            </li>
        </ul>
    </div>
</div>