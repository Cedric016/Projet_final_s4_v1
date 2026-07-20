<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Comptes clients</h2>
    <a href="<?= site_url('clients/create') ?>" class="btn">+ Nouveau compte</a>
</div>

<table>
    <thead><tr><th>Nom</th><th>Téléphone</th><th>Solde (Ar)</th><th>Statut</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($clients as $c): ?>
            <tr>
                <td><?= esc($c['nom']) ?></td>
                <td><?= esc($c['telephone']) ?></td>
                <td><?= number_format($c['solde'], 0, ',', ' ') ?></td>
                <td><?= $c['actif'] ? '<span class="badge badge-ok">Actif</span>' : '<span class="badge badge-no">Inactif</span>' ?></td>
                <td class="actions">
                    <a href="<?= site_url('clients/edit/' . $c['id']) ?>" class="btn btn-warning btn-small">Modifier</a>
                    <a href="<?= site_url('clients/delete/' . $c['id']) ?>" class="btn btn-danger btn-small" onclick="return confirm('Supprimer ce compte ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($clients)): ?><tr><td colspan="5">Aucun client enregistré.</td></tr><?php endif; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
