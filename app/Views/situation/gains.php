<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Situation des gains par opérateur</h2>

<div class="card">
    <h3>Gains — notre opérateur (transferts internes)</h3>
    <?php if (empty($gainsOperateur)): ?>
        <p>Aucun gain enregistré pour notre opérateur.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Type d'opération</th><th>Total frais (Ar)</th><th>Gain opérateur (Ar)</th></tr></thead>
            <tbody>
                <?php foreach ($gainsOperateur as $g): ?>
                    <tr>
                        <td><?= esc($g['libelle']) ?></td>
                        <td><?= number_format($g['total_frais'], 0, ',', ' ') ?></td>
                        <td><?= number_format($g['total_gain'], 0, ',', ' ') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr><th>Total</th><th colspan="2"><?= number_format($totalOperateur, 0, ',', ' ') ?> Ar</th></tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Gains — autres opérateurs (transferts sortants)</h3>
    <?php if (empty($gainsAutres)): ?>
        <p>Aucun transfert vers un autre opérateur pour l'instant.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Préfixe</th><th>Description</th><th>Commission supp. (Ar)</th><th>Gain opérateur (Ar)</th></tr></thead>
            <tbody>
                <?php foreach ($gainsAutres as $g): ?>
                    <tr>
                        <td><strong><?= esc($g['prefixe']) ?></strong></td>
                        <td><?= esc($g['description'] ?? '') ?></td>
                        <td><?= number_format($g['total_commission'], 0, ',', ' ') ?></td>
                        <td><?= number_format($g['total_gain'], 0, ',', ' ') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr><th colspan="3">Total autres opérateurs</th><th><?= number_format($totalAutres, 0, ',', ' ') ?> Ar</th></tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Total général des gains</h3>
    <p><strong><?= number_format($totalGeneral, 0, ',', ' ') ?> Ar</strong></p>
</div>
<?= $this->endSection() ?>
