<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Préfixes valides de l'opérateur</h2>
    <a href="<?= site_url('prefixes/create') ?>" class="btn">+ Ajouter un préfixe</a>
</div>

<table>
    <thead><tr><th>Préfixe</th><th>Description</th><th>Statut</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($prefixes as $p): ?>
            <tr>
                <td><strong><?= esc($p['prefixe']) ?></strong></td>
                <td><?= esc($p['description'] ?? '') ?></td>
                <td><?= $p['actif'] ? '<span class="badge badge-ok">Actif</span>' : '<span class="badge badge-no">Inactif</span>' ?></td>
                <td class="actions">
                    <a href="<?= site_url('prefixes/edit/' . $p['id']) ?>" class="btn btn-warning btn-small">Modifier</a>
                    <a href="<?= site_url('prefixes/delete/' . $p['id']) ?>" class="btn btn-danger btn-small" onclick="return confirm('Supprimer ce préfixe ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($prefixes)): ?><tr><td colspan="4">Aucun préfixe configuré.</td></tr><?php endif; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
