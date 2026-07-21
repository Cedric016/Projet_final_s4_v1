<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shareo - <?= esc($title ?? 'Opérateur') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --shareo-primary: #1a2b4a;
            --shareo-primary-light: #2c3e6b;
            --shareo-secondary: #0d7c66;
            --shareo-success: #0d7c66;
            --shareo-warning: #b8860b;
            --shareo-danger: #8b3a3a;
            --shareo-bg: #f5f6fa;
            --shareo-card: #ffffff;
            --shareo-text: #1a1a2e;
            --shareo-text-secondary: #4a5568;
            --shareo-border: #d1d5db;
            --shareo-focus: #0d7c66;
            --shareo-radius: 8px;
            --shareo-shadow: 0 1px 3px rgba(0,0,0,.08);
            --shareo-shadow-hover: 0 4px 12px rgba(0,0,0,.12);
            --sidebar-width: 260px;
            --header-height: 64px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, Arial, sans-serif;
            background: var(--shareo-bg);
            color: var(--shareo-text);
            line-height: 1.6;
            min-height: 100vh;
        }

        a { color: inherit; text-decoration: none; }

        /* Header */
        .top-header {
            background: var(--shareo-primary);
            color: #fff;
            height: var(--header-height);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,.12);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: var(--shareo-radius);
        }

        .menu-toggle:hover, .menu-toggle:focus {
            background: rgba(255,255,255,.15);
            outline: none;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .brand svg {
            width: 28px;
            height: 28px;
            fill: none;
            stroke: #fff;
            stroke-width: 2;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-icon-btn {
            position: relative;
            background: none;
            border: none;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            padding: 8px;
            border-radius: var(--shareo-radius);
            transition: background .15s ease;
        }

        .header-icon-btn:hover, .header-icon-btn:focus {
            background: rgba(255,255,255,.15);
            outline: none;
        }

        .header-icon-btn:focus-visible {
            outline: 2px solid #fff;
            outline-offset: 2px;
        }

        .badge-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: var(--shareo-danger);
            border-radius: 50%;
            border: 2px solid var(--shareo-primary);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: var(--shareo-radius);
            transition: background .15s ease;
        }

        .user-menu:hover, .user-menu:focus {
            background: rgba(255,255,255,.1);
            outline: none;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--shareo-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.3;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
        }

        .user-role {
            font-size: 11px;
            opacity: 0.8;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: var(--shareo-card);
            border-right: 1px solid var(--shareo-border);
            overflow-y: auto;
            z-index: 900;
            transition: transform .25s ease;
        }

        .sidebar-section {
            padding: 16px 12px 8px;
        }

        .sidebar-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--shareo-text-secondary);
            padding: 0 12px 8px;
            margin-top: 4px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: var(--shareo-radius);
            color: var(--shareo-text-secondary);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 2px;
            transition: all .15s ease;
        }

        .sidebar-nav a i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-nav a:hover, .sidebar-nav a:focus {
            background: var(--shareo-bg);
            color: var(--shareo-primary);
            outline: none;
        }

        .sidebar-nav a:focus-visible {
            outline: 2px solid var(--shareo-focus);
            outline-offset: -2px;
        }

        .sidebar-nav a.active {
            background: rgba(13,124,102,.08);
            color: var(--shareo-secondary);
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 16px;
            margin-top: auto;
            border-top: 1px solid var(--shareo-border);
            font-size: 12px;
            color: var(--shareo-text-secondary);
            text-align: center;
        }

        /* Main content */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            padding-top: var(--header-height);
            min-height: 100vh;
        }

        .main-content {
            padding: 24px;
            max-width: 1200px;
        }

        /* Cards */
        .card {
            background: var(--shareo-card);
            border-radius: var(--shareo-radius);
            padding: 24px;
            box-shadow: var(--shareo-shadow);
            margin-bottom: 20px;
            border: 1px solid var(--shareo-border);
        }

        .card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--shareo-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }

        th, td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid var(--shareo-border);
        }

        th {
            background: #f8fafc;
            font-weight: 600;
            color: var(--shareo-text-secondary);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        tr:hover td {
            background: rgba(26,43,74,.02);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border: none;
            border-radius: var(--shareo-radius);
            background: var(--shareo-secondary);
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background .15s ease, transform .05s ease;
        }

        .btn:hover {
            background: #0a6b5a;
        }

        .btn:active {
            transform: translateY(1px);
        }

        .btn:focus-visible {
            outline: 3px solid var(--shareo-focus);
            outline-offset: 2px;
        }

        .btn-primary { background: var(--shareo-secondary); }
        .btn-primary:hover { background: #0a6b5a; }

        .btn-success { background: var(--shareo-success); }
        .btn-success:hover { background: #0a6b5a; }

        .btn-danger { background: var(--shareo-danger); }
        .btn-danger:hover { background: #6f2e2e; }

        .btn-warning { background: var(--shareo-warning); color: #fff; }
        .btn-warning:hover { background: #9a7009; }

        .btn-small { padding: 6px 12px; font-size: 13px; }

        /* Forms */
        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
            color: var(--shareo-text);
        }

        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--shareo-border);
            border-radius: var(--shareo-radius);
            font-size: 14px;
            background: #fff;
            color: var(--shareo-text);
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--shareo-secondary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(13,124,102,.15);
        }

        textarea {
            resize: vertical;
        }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: var(--shareo-radius);
            margin-bottom: 16px;
            font-size: 14px;
            border-left: 4px solid transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #e6f4f1;
            color: #0d5c4c;
            border-left-color: var(--shareo-success);
        }

        .alert-danger {
            background: #fbeaea;
            color: #6f2e2e;
            border-left-color: var(--shareo-danger);
        }

        /* Stats */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat {
            background: var(--shareo-card);
            border-radius: var(--shareo-radius);
            padding: 20px;
            box-shadow: var(--shareo-shadow);
            border: 1px solid var(--shareo-border);
            transition: box-shadow .15s ease, transform .15s ease;
        }

        .stat:hover {
            box-shadow: var(--shareo-shadow-hover);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--shareo-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .stat-icon.primary { background: rgba(26,43,74,.08); color: var(--shareo-primary); }
        .stat-icon.success { background: #e6f4f1; color: var(--shareo-secondary); }
        .stat-icon.warning { background: #fdf6e3; color: var(--shareo-warning); }
        .stat-icon.danger { background: #fbeaea; color: var(--shareo-danger); }

        .stat .num {
            font-size: 26px;
            font-weight: 700;
            color: var(--shareo-primary);
            margin-bottom: 4px;
        }

        .stat .label {
            color: var(--shareo-text-secondary);
            font-size: 13px;
            font-weight: 500;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-ok {
            background: #e6f4f1;
            color: #0d5c4c;
        }

        .badge-no {
            background: #fbeaea;
            color: #6f2e2e;
        }

        /* Typography */
        h2 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--shareo-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--shareo-primary);
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .form-card {
            max-width: 650px;
        }

        .text-muted {
            color: var(--shareo-text-secondary);
            font-size: 13px;
            margin: 8px 0 4px;
        }

        .mt-4 { margin-top: 16px; }
        .mb-4 { margin-bottom: 16px; }
        .mt-2 { margin-top: 8px; }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: normal;
            cursor: pointer;
        }

        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--shareo-secondary);
            cursor: pointer;
        }

        .solde-box {
            font-size: 32px;
            font-weight: 700;
            color: var(--shareo-primary);
        }

        .login-box {
            max-width: 420px;
            margin: 40px auto;
            background: var(--shareo-card);
            border-radius: var(--shareo-radius);
            padding: 32px;
            box-shadow: var(--shareo-shadow);
            border: 1px solid var(--shareo-border);
        }

        .actions a {
            margin-right: 8px;
            font-weight: 500;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        /* Overlay mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.4);
            z-index: 899;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .main-wrapper {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .user-info {
                display: none;
            }

            main { padding: 16px; }
            .stats { grid-template-columns: 1fr; }
            table { font-size: 13px; }
            th, td { padding: 8px 10px; }
        }
    </style>
</head>
<body>
    <header class="top-header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu">
                <i class="fas fa-bars"></i>
            </button>
            <div class="brand">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                Shareo
            </div>
        </div>
        <div class="header-right">
            <button class="header-icon-btn" aria-label="Notifications">
                <i class="fas fa-bell"></i>
                <span class="badge-dot"></span>
            </button>
            <div class="user-menu" tabindex="0" role="button" aria-label="Menu utilisateur">
                <div class="user-avatar">OP</div>
                <div class="user-info">
                    <span class="user-name">Opérateur</span>
                    <span class="user-role">Administrateur</span>
                </div>
                <i class="fas fa-chevron-down" style="font-size:12px;opacity:.8;"></i>
            </div>
        </div>
    </header>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar" role="navigation" aria-label="Navigation latérale">
        <div class="sidebar-section">
            <div class="sidebar-title">Menu principal</div>
            <nav class="sidebar-nav">
                <a href="<?= site_url('dashboard') ?>" class="<?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i> Tableau de bord
                </a>
                <a href="<?= site_url('prefixes') ?>" class="<?= ($active ?? '') === 'prefixes' ? 'active' : '' ?>">
                    <i class="fas fa-phone-alt"></i> Préfixes
                </a>
                <a href="<?= site_url('types') ?>" class="<?= ($active ?? '') === 'types' ? 'active' : '' ?>">
                    <i class="fas fa-exchange-alt"></i> Types d'opération
                </a>
                <a href="<?= site_url('baremes') ?>" class="<?= ($active ?? '') === 'baremes' ? 'active' : '' ?>">
                    <i class="fas fa-calculator"></i> Barèmes de frais
                </a>
                <a href="<?= site_url('clients') ?>" class="<?= ($active ?? '') === 'clients' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Comptes clients
                </a>
                <a href="<?= site_url('operations') ?>" class="<?= ($active ?? '') === 'operations' ? 'active' : '' ?>">
                    <i class="fas fa-paper-plane"></i> Opérations
                </a>
            </nav>
        </div>
        <div class="sidebar-section">
            <div class="sidebar-title">Reporting</div>
            <nav class="sidebar-nav">
                <a href="<?= site_url('situation/gains') ?>" class="<?= ($active ?? '') === 'situation_gains' ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i> Gains par opérateur
                </a>
                <a href="<?= site_url('situation/operateurs') ?>" class="<?= ($active ?? '') === 'situation_operateurs' ? 'active' : '' ?>">
                    <i class="fas fa-hand-holding-usd"></i> Montants à envoyer
                </a>
            </nav>
        </div>
        <div class="sidebar-section">
            <div class="sidebar-title">Système</div>
            <nav class="sidebar-nav">
                <a href="<?= site_url('settings') ?>" class="<?= ($active ?? '') === 'settings' ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i> Paramètres
                </a>
                <a href="<?= site_url('client/login') ?>" class="<?= ($active ?? '') === 'client' ? 'active' : '' ?>">
                    <i class="fas fa-user"></i> Espace Client
                </a>
            </nav>
        </div>
        <div class="sidebar-footer">
            Shareo v1.0
        </div>
    </aside>

    <div class="main-wrapper">
        <main class="main-content">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success" role="status" aria-live="polite">
                    <i class="fas fa-check-circle"></i> <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" role="alert" aria-live="assertive">
                    <i class="fas fa-exclamation-circle"></i> <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        menuToggle.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                toggleSidebar();
            }
        });
    </script>
</body>
</html>
