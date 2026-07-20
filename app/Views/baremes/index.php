<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Barèmes de frais par tranche de montant</h2>
    <a href="<?= site_url('baremes/create') ?>" class="btn">+ Ajouter un barème</a>
</div>

<?php foreach ($types as $t): ?>
    <div class="card">
        <h3><?= esc($t['libelle']) ?> <small style="color:#888">(<?= esc($t['code']) ?>) — <?= $t['frais_actif'] ? 'frais actifs' : 'sans frais' ?></small></h3>
        <table>
            <thead><tr><th>Tranche (Ar)</th><th>Frais (Ar)</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($t['baremes'] as $b): ?>
                    <tr>
                        <td><?= number_format($b['montant_min'], 0, ',', ' ') ?> → <?= $b['montant_max'] ? number_format($b['montant_max'], 0, ',', ' ') : 'et plus' ?></td>
                        <td><?= number_format($b['frais'], 0, ',', ' ') ?></td>
                        <td class="actions">
                            <a href="<?= site_url('baremes/edit/' . $b['id']) ?>" class="btn btn-warning btn-small">Modifier</a>
                            <a href="<?= site_url('baremes/delete/' . $b['id']) ?>" class="btn btn-danger btn-small" onclick="return confirm('Supprimer ce barème ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($t['baremes'])): ?><tr><td colspan="3">Aucun barème pour ce type.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endforeach; ?>
<?= $this->endSection() ?>
