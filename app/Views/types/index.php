<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="header-actions">
    <h2><i class="fas fa-exchange-alt"></i> Types d'opération</h2>
    <a href="<?= site_url('types/create') ?>" class="btn"><i class="fas fa-plus"></i> Ajouter un type</a>
</div>

<div class="card">
    <table>
        <thead><tr><th>Code</th><th>Libellé</th><th>Frais appliqués ?</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($types as $t): ?>
                <tr>
                    <td><code><?= esc($t['code']) ?></code></td>
                    <td><?= esc($t['libelle']) ?></td>
                    <td><?= $t['frais_actif'] ? '<span class="badge badge-ok">Oui</span>' : '<span class="badge badge-no">Gratuit</span>' ?></td>
                    <td class="actions">
                        <a href="<?= site_url('types/edit/' . $t['id']) ?>" class="btn btn-warning btn-small"><i class="fas fa-edit"></i> Modifier</a>
                        <a href="<?= site_url('types/delete/' . $t['id']) ?>" class="btn btn-danger btn-small" onclick="return confirm('Supprimer ce type ?')"><i class="fas fa-trash"></i> Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($types)): ?><tr><td colspan="4">Aucun type configuré.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
