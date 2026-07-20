<?= $this->extend('client/layout') ?>

<?= $this->section('content') ?>
<div class="login-box card">
    <h2>Connexion</h2>
    <p style="margin-bottom:15px;color:#666;">Saisissez votre numéro de téléphone pour accéder à votre compte. Aucune inscription n'est nécessaire.</p>
    <form method="post" action="<?= site_url('client/authentifier') ?>">
        <div class="form-group">
            <label>Numéro de téléphone</label>
            <input type="text" name="telephone" placeholder="ex: 03312345" value="<?= old('telephone') ?>" required>
        </div>
        <button type="submit" class="btn">Se connecter</button>
    </form>
</div>
<?= $this->endSection() ?>
