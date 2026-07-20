<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2><?= $bareme ? 'Modifier le barème' : 'Nouveau barème de frais' ?></h2>

<form method="post" action="<?= $bareme ? site_url('baremes/update/' . $bareme['id']) : site_url('baremes/store') ?>" style="max-width:500px;">
    <div class="form-group">
        <label>Type d'opération</label>
        <select name="type_operation_id" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= $t['id'] ?>" <?= (isset($bareme) && $bareme['type_operation_id'] == $t['id']) ? 'selected' : '' ?>><?= esc($t['libelle']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Montant minimum (Ar)</label>
        <input type="number" name="montant_min" min="0" value="<?= esc($bareme['montant_min'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label>Montant maximum (Ar, vide = pas de plafond)</label>
        <input type="number" name="montant_max" min="0" value="<?= esc($bareme['montant_max'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label>Frais (Ar)</label>
        <input type="number" name="frais" min="0" value="<?= esc($bareme['frais'] ?? '') ?>" required>
    </div>
    <button type="submit" class="btn">Enregistrer</button>
    <a href="<?= site_url('baremes') ?>" class="btn btn-warning">Annuler</a>
</form>
<?= $this->endSection() ?>
