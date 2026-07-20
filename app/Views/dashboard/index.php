<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Tableau de bord de l'opérateur</h2>

<div class="stats">
    <div class="stat"><div class="num"><?= number_format($totalGain, 0, ',', ' ') ?> Ar</div><div class="label">Gain total opérateur (frais)</div></div>
    <div class="stat"><div class="num"><?= $nbTransactions ?></div><div class="label">Transactions réussies</div></div>
    <div class="stat"><div class="num"><?= $totalClients ?></div><div class="label">Comptes clients</div></div>
    <div class="stat"><div class="num"><?= number_format($soldeGlobal, 0, ',', ' ') ?> Ar</div><div class="label">Solde global clients</div></div>
    <div class="stat"><div class="num"><?= $nbPrefixes ?></div><div class="label">Préfixes actifs</div></div>
</div>

<div class="card">
    <h3>Gain par type d'opération</h3>
    <?php if (empty($gainsParType)): ?>
        <p>Aucune opération génératrice de gain pour l'instant.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Type d'opération</th><th>Gain (Ar)</th></tr></thead>
            <tbody>
                <?php foreach ($gainsParType as $g): ?>
                    <tr><td><?= esc($g['libelle']) ?></td><td><?= number_format($g['total'], 0, ',', ' ') ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Dernières transactions</h3>
    <?php if (empty($dernieres)): ?>
        <p>Aucune transaction enregistrée.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Référence</th><th>Type</th><th>Client</th><th>Montant</th><th>Frais</th><th>Gain</th><th>Date</th></tr></thead>
            <tbody>
                <?php foreach ($dernieres as $t): ?>
                    <tr>
                        <td><?= esc($t['reference']) ?></td>
                        <td><?= esc($t['type_libelle']) ?></td>
                        <td><?= esc($t['client_nom']) ?></td>
                        <td><?= number_format($t['montant'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($t['frais'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($t['gain_operateur'], 0, ',', ' ') ?> Ar</td>
                        <td><?= esc($t['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
