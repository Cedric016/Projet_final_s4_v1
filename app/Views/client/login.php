<?= $this->extend('client/layout') ?>

<?= $this->section('content') ?>
<div class="login-box card">
    <h2>Connexion</h2>
    <p style="margin-bottom:15px;color:#666;">Saisissez votre numéro de téléphone pour accécer à votre compte. Aucune inscription n'est nécessaire.</p>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('client/authentifier') ?>">
        <div class="form-group">
            <label>Numéro de téléphone</label>
            <input type="text" name="telephone" placeholder="ex: 033 00 000 00" value="<?= old('telephone') ?>" maxlength="20" required>
            <small style="color:#888;">Format : préfixe (033, 037) + 7 chiffres = 10 chiffres au total. Les espaces sont acceptés.</small>
        </div>
        <button type="submit" class="btn">Se connecter</button>
    </form>
    <p style="margin-top:15px;">
        <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary btn-small">&larr; Retour à l'espace opérateur</a>
    </p>
</div>
<?= $this->endSection() ?>
