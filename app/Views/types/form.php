<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2><i class="fas fa-exchange-alt"></i> <?= $type ? 'Modifier le type d\'opération' : 'Nouveau type d\'opération' ?></h2>

<form method="post" action="<?= $type ? site_url('types/update/' . $type['id']) : site_url('types/store') ?>" class="form-card">
    <div class="form-group">
        <label>Code (ex: depot, retrait, transfert)</label>
        <input type="text" name="code" value="<?= esc($type['code'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label>Libellé</label>
        <input type="text" name="libelle" value="<?= esc($type['libelle'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="frais_actif" value="1" <?= (!isset($type) || $type['frais_actif']) ? 'checked' : '' ?>>
            Appliquer des frais (barème)
        </label>
    </div>
    <button type="submit" class="btn"><i class="fas fa-save"></i> Enregistrer</button>
    <a href="<?= site_url('types') ?>" class="btn btn-warning">Annuler</a>
</form>
<?= $this->endSection() ?>
