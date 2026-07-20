<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Types d'opération</h2>
    <a href="<?= site_url('types/create') ?>" class="btn">+ Ajouter un type</a>
</div>

<table>
    <thead><tr><th>Code</th><th>Libellé</th><th>Frais appliqués ?</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($types as $t): ?>
            <tr>
                <td><code><?= esc($t['code']) ?></code></td>
                <td><?= esc($t['libelle']) ?></td>
                <td><?= $t['frais_actif'] ? '<span class="badge badge-ok">Oui</span>' : '<span class="badge badge-no">Gratuit</span>' ?></td>
                <td class="actions">
                    <a href="<?= site_url('types/edit/' . $t['id']) ?>" class="btn btn-warning btn-small">Modifier</a>
                    <a href="<?= site_url('types/delete/' . $t['id']) ?>" class="btn btn-danger btn-small" onclick="return confirm('Supprimer ce type ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($types)): ?><tr><td colspan="4">Aucun type configuré.</td></tr><?php endif; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
