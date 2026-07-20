<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Espace Client</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Segoe UI, Arial, sans-serif; background: #f4f6f9; color: #333; }
        header { background: #198754; color: #fff; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 20px; }
        header a { color: #fff; text-decoration: none; background: rgba(255,255,255,.2); padding: 8px 14px; border-radius: 5px; }
        nav { background: #157347; padding: 0 25px; display: flex; flex-wrap: wrap; gap: 5px; }
        nav a { color: #fff; text-decoration: none; padding: 12px 16px; display: block; }
        nav a:hover, nav a.active { background: rgba(255,255,255,.15); }
        main { padding: 25px; max-width: 1000px; margin: 0 auto; }
        .card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; }
        .btn { display: inline-block; padding: 8px 14px; border: none; border-radius: 5px; background: #198754; color: #fff; text-decoration: none; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #157347; }
        .btn-secondary { background: #6c757d; }
        .btn-small { padding: 5px 10px; font-size: 12px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; }
        input, select { width: 100%; padding: 9px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; }
        .alert { padding: 12px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d1e7dd; color: #0f5132; }
        .alert-danger { background: #f8d7da; color: #842029; }
        .solde-box { font-size: 28px; font-weight: 700; color: #198754; }
        .actions a { margin-right: 5px; }
        h2 { margin-bottom: 15px; }
        .login-box { max-width: 400px; margin: 60px auto; }
    </style>
</head>
<body>
    <header>
        <h1>💚 Espace Client Mobile Money</h1>
        <?php if (client_connecte()): ?>
            <a href="<?= site_url('client/deconnecter') ?>">Déconnexion</a>
        <?php endif; ?>
    </header>
    <?php if (client_connecte()): ?>
        <nav>
            <a href="<?= site_url('client') ?>" class="<?= ($active ?? '') === 'client' ? 'active' : '' ?>">Accueil</a>
            <a href="<?= site_url('client/operations') ?>" class="<?= ($active ?? '') === 'operations' ? 'active' : '' ?>">Opérations</a>
            <a href="<?= site_url('client/historique') ?>" class="<?= ($active ?? '') === 'historique' ? 'active' : '' ?>">Historique</a>
        </nav>
    <?php endif; ?>
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
