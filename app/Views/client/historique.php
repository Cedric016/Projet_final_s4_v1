<?= $this->extend('client/layout') ?>

<?= $this->section('content') ?>
<h2>Mon historique</h2>

<table>
    <thead><tr><th>Référence</th><th>Type</th><th>Destinataire</th><th>Montant</th><th>Frais</th><th>Commission 1%</th><th>Date</th></tr></thead>
    <tbody>
        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= esc($t['reference']) ?></td>
                <td><?= esc($t['type_libelle']) ?></td>
                <td><?= esc($t['dest_nom'] ?? '—') ?></td>
                <td><?= number_format($t['montant'], 0, ',', ' ') ?> Ar</td>
                <td><?= number_format($t['frais'], 0, ',', ' ') ?> Ar</td>
                <td><?= number_format($t['commission_autre'] ?? 0, 0, ',', ' ') ?> Ar</td>
                <td><?= esc($t['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($transactions)): ?><tr><td colspan="7">Aucune opération enregistrée.</td></tr><?php endif; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
