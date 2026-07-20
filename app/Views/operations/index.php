<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Effectuer une opération</h2>
    <a href="<?= site_url('operations/historique') ?>" class="btn btn-warning">Historique</a>
</div>

<form method="post" action="<?= site_url('operations/executer') ?>" style="max-width:550px;">
    <div class="form-group">
        <label>Type d'opération</label>
        <select name="type_operation_id" id="type_op" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= $t['id'] ?>" data-code="<?= esc($t['code']) ?>"><?= esc($t['libelle']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Client (expéditeur / compte concerné)</label>
        <select name="client_id" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($clients as $c): ?>
                <option value="<?= $c['id'] ?>"><?= esc($c['nom']) ?> (<?= esc($c['telephone']) ?>) — solde: <?= number_format($c['solde'], 0, ',', ' ') ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group" id="dest_block" style="display:none;">
        <label>Destinataire (pour transfert)</label>
        <select name="client_dest_id">
            <option value="">-- Choisir --</option>
            <?php foreach ($clients as $c): ?>
                <option value="<?= $c['id'] ?>"><?= esc($c['nom']) ?> (<?= esc($c['telephone']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Montant (Ar)</label>
        <input type="number" name="montant" min="0" step="0.01" required>
    </div>
    <button type="submit" class="btn btn-success">Exécuter l'opération</button>
</form>

<script>
document.getElementById('type_op').addEventListener('change', function () {
    var code = this.options[this.selectedIndex].getAttribute('data-code');
    document.getElementById('dest_block').style.display = (code === 'transfert') ? 'block' : 'none';
});
</script>
<?= $this->endSection() ?>
