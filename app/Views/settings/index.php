<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2><i class="fas fa-cog"></i> Paramètres de l'opérateur</h2>

<div class="card">
    <h3><i class="fas fa-exchange-alt"></i> Transferts vers les autres opérateurs</h3>
    <p>Ce pourcentage s'ajoute aux frais habituels (barème) lors d'un transfert vers un numéro appartenant à un autre opérateur (préfixes marqués « Autre opérateur »).</p>

    <form method="post" action="<?= site_url('settings/update') ?>" class="form-card">
        <div class="form-group">
            <label>Commission supplémentaire (%) pour transfert vers un autre opérateur</label>
            <input type="number" step="0.01" min="0" name="commission_autre_operateur" value="<?= esc($commission) ?>" required>
        </div>
        <button type="submit" class="btn"><i class="fas fa-save"></i> Enregistrer</button>
    </form>
</div>
<?= $this->endSection() ?>
