<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2><i class="fas fa-phone-alt"></i> <?= $prefixe ? 'Modifier le préfixe' : 'Nouveau préfixe' ?></h2>

<form method="post" action="<?= $prefixe ? site_url('prefixes/update/' . $prefixe['id']) : site_url('prefixes/store') ?>" class="form-card">
    <div class="form-group">
        <label>Préfixe (ex: 033, 037)</label>
        <input type="text" name="prefixe" value="<?= esc($prefixe['prefixe'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label>Description</label>
        <input type="text" name="description" value="<?= esc($prefixe['description'] ?? '') ?>" placeholder="ex: Telma, Orange...">
    </div>
    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="autre_operateur" value="1" <?= (!isset($prefixe) || (int)($prefixe['autre_operateur'] ?? 0) === 1) ? 'checked' : '' ?>>
            Appartient à un autre opérateur (032, 031, …)
        </label>
    </div>
    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="actif" value="1" <?= (!isset($prefixe) || $prefixe['actif']) ? 'checked' : '' ?>>
            Actif
        </label>
    </div>
    <button type="submit" class="btn"><i class="fas fa-save"></i> Enregistrer</button>
    <a href="<?= site_url('prefixes') ?>" class="btn btn-warning">Annuler</a>
</form>
<?= $this->endSection() ?>
