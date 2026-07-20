<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Montants à envoyer à chaque opérateur</h2>
<p>Total des montants transférés vers les préfixes de chaque autre opérateur (hors frais et commissions conservés par notre opérateur).</p>

<div class="card">
    <?php if (empty($montants)): ?>
        <p>Aucun transfert vers un autre opérateur pour l'instant.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Préfixe</th><th>Opérateur</th><th>Nb transferts</th><th>Montant à envoyer (Ar)</th><th>Commission perçue (Ar)</th></tr></thead>
            <tbody>
                <?php foreach ($montants as $m): ?>
                    <tr>
                        <td><strong><?= esc($m['prefixe']) ?></strong></td>
                        <td><?= esc($m['description'] ?? '') ?></td>
                        <td><?= $m['nb_transferts'] ?></td>
                        <td><?= number_format($m['total_montant'], 0, ',', ' ') ?></td>
                        <td><?= number_format($m['total_commission'], 0, ',', ' ') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="3">Total</th>
                    <th><?= number_format($totalMontant, 0, ',', ' ') ?> Ar</th>
                    <th><?= number_format($totalCommission, 0, ',', ' ') ?> Ar</th>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
