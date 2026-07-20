<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Situation des gains par opérateur</h2>
<p>Répartition des frais et commission 1 % par opérateur de l'expéditeur.</p>

<div class="card">
    <h3>Gains par opérateur (transferts)</h3>
    <?php if (empty($gains)): ?>
        <p>Aucun transfert générateur de gain pour l'instant.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Préfixe</th><th>Opérateur</th><th>Total frais (Ar)</th><th>Total commission 1% (Ar)</th><th>Gain total (Ar)</th></tr></thead>
            <tbody>
                <?php foreach ($gains as $g): ?>
                    <tr>
                        <td><strong><?= esc($g['prefixe'] ?? 'N/A') ?></strong></td>
                        <td><?= esc($g['description'] ?? '') ?></td>
                        <td><?= number_format($g['total_frais'], 0, ',', ' ') ?></td>
                        <td><?= number_format($g['total_commission'], 0, ',', ' ') ?></td>
                        <td><strong><?= number_format($g['total_gain'], 0, ',', ' ') ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                <tr><th colspan="4">Total général</th><th><?= number_format($totalGeneral, 0, ',', ' ') ?> Ar</th></tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
