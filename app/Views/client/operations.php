<?= $this->extend('client/layout') ?>

<?= $this->section('content') ?>
<h2><i class="fas fa-paper-plane"></i> Mes opérations</h2>
<p class="mb-4">Solde actuel : <strong><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</strong></p>

<form method="post" action="<?= site_url('client/executer') ?>" class="form-card">
    <div class="form-group">
        <label>Type d'opération</label>
        <select name="type_operation_id" id="type_op" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= $t['id'] ?>" data-code="<?= esc($t['code']) ?>"><?= esc($t['libelle']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group" id="dest_block" style="display:none;">
        <label>Destinataire (pour transfert)</label>
        <p class="text-muted">Sélectionnez un contact ou saisissez un/plusieurs numéro(s) ci-dessous (séparés par virgule, espace ou saut de ligne). Le montant sera divisé équitablement entre chaque numéro (même opérateur uniquement).</p>
        <select name="client_dest_id">
            <option value="">-- Choisir un contact --</option>
            <?php foreach ($clients as $c): ?>
                <?php if ($c['id'] !== $client['id']): ?>
                    <option value="<?= $c['id'] ?>"><?= esc($c['nom']) ?> (<?= esc($c['telephone']) ?>)</option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <textarea name="numeros" rows="3" placeholder="Ex: 0331234567, 0377654321" class="mt-2"></textarea>
    </div>
    <div class="form-group">
        <label>Montant (Ar)</label>
        <input type="number" name="montant" id="montant" min="0" step="0.01" required>
    </div>
    <div class="form-group" id="retrait_block" style="display:none;">
        <label class="checkbox-label">
            <input type="checkbox" name="frais_inclus_retrait" id="frais_inclus" value="1">
            Inclure les frais de retrait dans le montant (vous recevez le montant saisi)
        </label>
    </div>
    <div class="form-group" id="transfert_block" style="display:none;">
        <label class="checkbox-label">
            <input type="checkbox" name="frais_inclus_transfert" id="frais_inclus_transfert" value="1">
            Inclure les frais de retrait du destinataire (uniquement pour même opérateur)
        </label>
    </div>
    <button type="submit" class="btn">Valider</button>
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
