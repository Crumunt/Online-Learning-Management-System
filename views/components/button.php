<button 
    class="btn btn-<?= $type ?>"
    id="<?= $id ?? 'tmpl_btn_' . random_int(10,100000000000) ?>"
    >
    <?= ucfirst($label) ?>
</button>