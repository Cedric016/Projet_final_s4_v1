<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2><?= $client ? 'Modifier le compte client' : 'Nouveau compte client' ?></h2>

<form method="post" action="<?= $client ? site_url('clients/update/' . $client['id']) : site_url('clients/store') ?>" style="max-width:500px;">
    <div class="form-group">
        <label>Nom</label>
        <input type="text" name="nom" value="<?= esc($client['nom'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label>Téléphone</label>
        <input type="text" name="telephone" value="<?= esc($client['telephone'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label>Solde initial (Ar)</label>
        <input type="number" step="0.01" name="solde" value="<?= esc($client['solde'] ?? 0) ?>">
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="actif" value="1" <?= (!isset($client) || $client['actif']) ? 'checked' : '' ?>> Actif</label>
    </div>
    <button type="submit" class="btn">Enregistrer</button>
    <a href="<?= site_url('clients') ?>" class="btn btn-warning">Annuler</a>
</form>
<?= $this->endSection() ?>
