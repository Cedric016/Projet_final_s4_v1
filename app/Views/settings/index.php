<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Paramètres de l'opérateur</h2>

<div class="card">
    <h3>Transferts vers les autres opérateurs</h3>
    <p>Ce pourcentage s'ajoute aux frais habituels (barème) lors d'un transfert vers un numéro appartenant à un autre opérateur (préfixes marqués « Autre opérateur »).</p>

    <form method="post" action="<?= site_url('settings/update') ?>" style="max-width:500px;">
        <div class="form-group">
            <label>Commission supplémentaire (% ) pour transfert vers un autre opérateur</label>
            <input type="number" step="0.01" min="0" name="commission_autre_operateur" value="<?= esc($commission) ?>" required>
        </div>
        <button type="submit" class="btn">Enregistrer</button>
    </form>
</div>
<?= $this->endSection() ?>
