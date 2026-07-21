<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Effectuer une opération</h2>
    <a href="<?= site_url('operations/historique') ?>" class="btn btn-warning">Historique</a>
</div>

<form method="post" action="<?= site_url('operations/executer') ?>" style="max-width:650px;">
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
        <select name="client_dest_id" id="dest_select">
            <option value="">-- Choisir --</option>
            <?php foreach ($clients as $c): ?>
                <option value="<?= $c['id'] ?>"><?= esc($c['nom']) ?> (<?= esc($c['telephone']) ?>)</option>
            <?php endforeach; ?>
        </select>
        <p style="margin:8px 0 4px;color:#666;font-size:13px;">Ou saisissez un/plusieurs numéro(s) (séparés par virgule, espace ou saut de ligne). Pour plusieurs destinataires, le montant sera divisé équitablement.</p>
        <textarea name="numeros" rows="3" placeholder="Ex: 0331234567, 0377654321" style="width:100%;"></textarea>
    </div>
    <div class="form-group">
        <label>Montant (Ar)</label>
        <input type="number" name="montant" id="montant" min="0" step="0.01" required>
    </div>
    <div class="form-group" id="retrait_block" style="display:none;">
        <label style="display:flex;align-items:center;gap:8px;font-weight:normal;">
            <input type="checkbox" name="frais_inclus_retrait" id="frais_inclus" value="1" style="width:auto;">
            Inclure les frais de retrait dans le montant (vous recevez le montant saisi)
        </label>
    </div>
    <div class="form-group" id="transfert_block" style="display:none;">
        <label style="display:flex;align-items:center;gap:8px;font-weight:normal;">
            <input type="checkbox" name="frais_inclus_transfert" id="frais_inclus_transfert" value="1" style="width:auto;">
            Inclure les frais de retrait du destinataire (uniquement pour même opérateur)
        </label>
    </div>
    <button type="submit" class="btn btn-success">Exécuter l'opération</button>
</form>

<script>
document.getElementById('type_op').addEventListener('change', function () {
    var code = this.options[this.selectedIndex].getAttribute('data-code');
    document.getElementById('dest_block').style.display = (code === 'transfert') ? 'block' : 'none';
    document.getElementById('retrait_block').style.display = (code === 'retrait') ? 'block' : 'none';
    document.getElementById('transfert_block').style.display = (code === 'transfert') ? 'block' : 'none';
});
</script>
<?= $this->endSection() ?>
