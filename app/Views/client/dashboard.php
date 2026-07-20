<?= $this->extend('client/layout') ?>

<?= $this->section('content') ?>
<div class="card">
    <h2>Bonjour <?= esc($client['nom']) ?></h2>
    <p style="color:#666;">Téléphone : <?= esc($client['telephone']) ?></p>
    <p style="margin-top:15px;">Votre solde</p>
    <div class="solde-box"><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</div>
    <p style="margin-top:15px;">
        <a href="<?= site_url('client/operations') ?>" class="btn">Faire une opération</a>
        <a href="<?= site_url('client/historique') ?>" class="btn btn-secondary">Voir l'historique</a>
    </p>
</div>

<div class="card">
    <h3>Dernières opérations</h3>
    <?php if (empty($historique)): ?>
        <p>Aucune opération pour le moment.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Type</th><th>Montant</th><th>Frais</th><th>Date</th></tr></thead>
            <tbody>
                <?php foreach ($historique as $t): ?>
                    <tr>
                        <td><?= esc($t['type_libelle']) ?></td>
                        <td><?= number_format($t['montant'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($t['frais'], 0, ',', ' ') ?> Ar</td>
                        <td><?= esc($t['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
