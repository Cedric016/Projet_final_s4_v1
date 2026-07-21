<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="header-actions">
    <h2><i class="fas fa-phone-alt"></i> Préfixes valides</h2>
    <a href="<?= site_url('prefixes/create') ?>" class="btn"><i class="fas fa-plus"></i> Ajouter un préfixe</a>
</div>

<div class="card">
    <table>
        <thead><tr><th>Préfixe</th><th>Description</th><th>Opérateur</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($prefixes as $p): ?>
                <tr>
                    <td><strong><?= esc($p['prefixe']) ?></strong></td>
                    <td><?= esc($p['description'] ?? '') ?></td>
                    <td><?= (int)($p['autre_operateur'] ?? 0) === 1 ? '<span class="badge badge-no">Autre opérateur</span>' : '<span class="badge badge-ok">Notre opérateur</span>' ?></td>
                    <td><?= $p['actif'] ? '<span class="badge badge-ok">Actif</span>' : '<span class="badge badge-no">Inactif</span>' ?></td>
                    <td class="actions">
                        <a href="<?= site_url('prefixes/edit/' . $p['id']) ?>" class="btn btn-warning btn-small"><i class="fas fa-edit"></i> Modifier</a>
                        <a href="<?= site_url('prefixes/delete/' . $p['id']) ?>" class="btn btn-danger btn-small" onclick="return confirm('Supprimer ce préfixe ?')"><i class="fas fa-trash"></i> Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($prefixes)): ?><tr><td colspan="5">Aucun préfixe configuré.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
