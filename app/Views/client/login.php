<?= $this->extend('client/layout') ?>

<?= $this->section('content') ?>
<div class="login-box card">
    <h2><i class="fas fa-mobile-alt"></i> Connexion</h2>
    <p class="mb-4">Saisissez votre numéro de téléphone pour accéder à votre compte. Aucune inscription n'est nécessaire.</p>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('client/authentifier') ?>">
        <div class="form-group">
            <label>Numéro de téléphone</label>
            <input type="text" name="telephone" placeholder="ex: 033 00 000 00" value="<?= old('telephone') ?>" maxlength="20" required>
            <small>Format : préfixe (033, 037) + 7 chiffres = 10 chiffres au total. Les espaces sont acceptés.</small>
        </div>
        <button type="submit" class="btn">Se connecter</button>
    </form>
    <p class="mt-4">
        <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary btn-small">&larr; Retour à l'espace opérateur</a>
    </p>
</div>
<?= $this->endSection() ?>
