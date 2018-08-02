<div class="row" style="width: 100%">
    <div class="container">
        <ul class="collection with-header">
            <li class="collection-header"><h4>Administratori</h4></li>
            <?php foreach($admins as $admin): ?>
            <li class="collection-item avatar">
                <i class="material-icons red circle">assignment_ind</i>
                <span class="title"><b><?= safe($admin['username']); ?></b></span>
                <p><?= safe($admin['organization_name']); ?></p>
                <form id="<?= "form_" . safe($admin['id']); ?>" method="post" action="?controller=deleteAdmin">
                    <input type="hidden" name="admin_id" value="<?= $admin['id'] ?>">
                </form>
                
                <!-- Delete button -->
                <a href="#" onclick="$('#form_<?= $admin['id'] ?>').submit();" class="waves-effect waves-light secondary-content"><i class="material-icons">delete</i></a>
            </li>
        <?php endforeach; ?>
            <li class="collection-item">
                <div class="center"><a href="?controller=createAdmin" class="btn orange modal-trigger"><i class="material-icons">add</i></a></div>
            </li>
        </ul>
    </div>
</div>