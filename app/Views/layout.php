<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - <?= esc($title ?? 'Opérateur') ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Segoe UI, Arial, sans-serif; background: #f4f6f9; color: #333; }
        header { background: #0d6efd; color: #fff; padding: 15px 25px; }
        header h1 { font-size: 20px; }
        nav { background: #0b5ed7; padding: 0 25px; display: flex; flex-wrap: wrap; gap: 5px; }
        nav a { color: #fff; text-decoration: none; padding: 12px 16px; display: block; }
        nav a:hover, nav a.active { background: rgba(255,255,255,.15); }
        nav a.btn-client { background: #198754; font-weight: 600; }
        nav a.btn-client:hover { background: #157347; }
        main { padding: 25px; max-width: 1200px; margin: 0 auto; }
        .card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; }
        .btn { display: inline-block; padding: 8px 14px; border: none; border-radius: 5px; background: #0d6efd; color: #fff; text-decoration: none; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #0b5ed7; }
        .btn-success { background: #198754; }
        .btn-danger { background: #dc3545; }
        .btn-warning { background: #ffc107; color: #000; }
        .btn-small { padding: 5px 10px; font-size: 12px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; }
        input, select, textarea { width: 100%; padding: 9px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; }
        .alert { padding: 12px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d1e7dd; color: #0f5132; }
        .alert-danger { background: #f8d7da; color: #842029; }
        .stats { display: flex; gap: 15px; flex-wrap: wrap; }
        .stat { background: #fff; border-radius: 8px; padding: 20px; flex: 1; min-width: 180px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .stat .num { font-size: 26px; font-weight: 700; color: #0d6efd; }
        .stat .label { color: #666; font-size: 13px; margin-top: 5px; }
        .actions a { margin-right: 5px; }
        .badge { padding: 3px 8px; border-radius: 4px; font-size: 12px; }
        .badge-ok { background: #d1e7dd; color: #0f5132; }
        .badge-no { background: #f8d7da; color: #842029; }
        h2 { margin-bottom: 15px; }
        .row-flex { display: flex; gap: 20px; flex-wrap: wrap; }
        .row-flex > * { flex: 1; min-width: 280px; }
    </style>
</head>
<body>
    <header><h1>💰 Simulateur Opérateur Mobile Money</h1></header>
    <nav>
        <a href="<?= site_url('dashboard') ?>" class="<?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>">Tableau de bord</a>
        <a href="<?= site_url('prefixes') ?>" class="<?= ($active ?? '') === 'prefixes' ? 'active' : '' ?>">Préfixes</a>
        <a href="<?= site_url('types') ?>" class="<?= ($active ?? '') === 'types' ? 'active' : '' ?>">Types d'opération</a>
        <a href="<?= site_url('baremes') ?>" class="<?= ($active ?? '') === 'baremes' ? 'active' : '' ?>">Barèmes de frais</a>
        <a href="<?= site_url('clients') ?>" class="<?= ($active ?? '') === 'clients' ? 'active' : '' ?>">Comptes clients</a>
        <a href="<?= site_url('operations') ?>" class="<?= ($active ?? '') === 'operations' ? 'active' : '' ?>">Opérations</a>
        <a href="<?= site_url('situation/gains') ?>" class="<?= ($active ?? '') === 'situation_gains' ? 'active' : '' ?>">Gains par opérateur</a>
        <a href="<?= site_url('situation/operateurs') ?>" class="<?= ($active ?? '') === 'situation_operateurs' ? 'active' : '' ?>">Montants à envoyer</a>
        <a href="<?= site_url('settings') ?>" class="<?= ($active ?? '') === 'settings' ? 'active' : '' ?>">Paramètres</a>
        <a href="<?= site_url('client/login') ?>" class="btn-client">Espace Client</a>
    </nav>
    <main>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </main>
</body>
</html>
