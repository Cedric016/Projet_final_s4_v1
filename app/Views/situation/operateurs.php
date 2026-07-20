<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Montants à envoyer à chaque opérateur</h2>
<p>Commission 1 % du montant transféré, par opérateur du récepteur.</p>

<div class="card">
    <?php if (empty($montants)): ?>
        <p>Aucun transfert inter-opérateur pour l'instant.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Préfixe</th><th>Opérateur</th><th>Montant total transféré (Ar)</th><th>Commission 1% (Ar)</th></tr></thead>
            <tbody>
                <?php foreach ($montants as $m): ?>
                    <tr>
                        <td><strong><?= esc($m['prefixe']) ?></strong></td>
                        <td><?= esc($m['description'] ?? '') ?></td>
                        <td><?= number_format($m['total_montant'], 0, ',', ' ') ?></td>
                        <td><strong><?= number_format($m['total_commission'], 0, ',', ' ') ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="3">Total commission</th>
                    <th><?= number_format($totalCommission, 0, ',', ' ') ?> Ar</th>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
