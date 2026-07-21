<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="header-actions">
    <h2><i class="fas fa-history"></i> Historique des opérations</h2>
    <a href="<?= site_url('operations') ?>" class="btn">+ Nouvelle opération</a>
</div>

<table>
    <thead><tr><th>Référence</th><th>Type</th><th>Client</th><th>Destinataire</th><th>Montant</th><th>Frais</th><th>Commission 1%</th><th>Frais retrait dest.</th><th>Gain opérateur</th><th>Date</th></tr></thead>
    <tbody>
        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= esc($t['reference']) ?></td>
                <td><?= esc($t['type_libelle']) ?></td>
                <td><?= esc($t['client_nom'] ?? '') ?></td>
                <td><?= esc($t['dest_nom'] ?? '—') ?></td>
                <td><?= number_format($t['montant'], 0, ',', ' ') ?> Ar</td>
                <td><?= number_format($t['frais'], 0, ',', ' ') ?> Ar</td>
                <td><?= number_format($t['commission_autre'] ?? 0, 0, ',', ' ') ?> Ar</td>
                <td><?= number_format($t['frais_retrait_dest'] ?? 0, 0, ',', ' ') ?> Ar</td>
                <td><?= number_format($t['gain_operateur'], 0, ',', ' ') ?> Ar</td>
                <td><?= esc($t['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($transactions)): ?><tr><td colspan="10">Aucune transaction enregistrée.</td></tr><?php endif; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
