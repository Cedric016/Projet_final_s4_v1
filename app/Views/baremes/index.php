<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="header-actions">
    <h2><i class="fas fa-calculator"></i> Barèmes de frais</h2>
    <a href="<?= site_url('baremes/create') ?>" class="btn"><i class="fas fa-plus"></i> Ajouter un barème</a>
</div>

<?php foreach ($types as $t): ?>
    <div class="card">
        <h3><i class="fas fa-file-invoice"></i> <?= esc($t['libelle']) ?> <small>(<?= esc($t['code']) ?>) — <?= $t['frais_actif'] ? 'frais actifs' : 'sans frais' ?></small></h3>
        <table>
            <thead><tr><th>Tranche (Ar)</th><th>Frais (Ar)</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($t['baremes'] as $b): ?>
                    <tr>
                        <td><?= number_format($b['montant_min'], 0, ',', ' ') ?> → <?= $b['montant_max'] ? number_format($b['montant_max'], 0, ',', ' ') : 'et plus' ?></td>
                        <td><?= number_format($b['frais'], 0, ',', ' ') ?></td>
                        <td class="actions">
                            <a href="<?= site_url('baremes/edit/' . $b['id']) ?>" class="btn btn-warning btn-small"><i class="fas fa-edit"></i> Modifier</a>
                            <a href="<?= site_url('baremes/delete/' . $b['id']) ?>" class="btn btn-danger btn-small" onclick="return confirm('Supprimer ce barème ?')"><i class="fas fa-trash"></i> Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($t['baremes'])): ?><tr><td colspan="3">Aucun barème pour ce type.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endforeach; ?>
<?= $this->endSection() ?>
