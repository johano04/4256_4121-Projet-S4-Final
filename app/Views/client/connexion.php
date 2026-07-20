<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center w-100">
    <div class="col-md-5">
        <div class="auth-brand">
            <span class="auth-brand-icon"><i class="bi bi-wallet2"></i></span>
            <span class="brand-word">Mobile Money</span>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h5 mb-1 text-center">Bienvenue</h1>
                <p class="text-muted text-center small mb-4">Entrez votre numéro de téléphone pour accéder à votre compte.</p>

                <form method="post" action="<?= site_url('connexion') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Numéro de téléphone</label>
                        <input type="text" name="telephone" class="form-control" placeholder="03......"
                               value="<?= old('telephone') ?>" maxlength="10" required>
                        <div class="form-text">Format : 10 chiffres, ex. 0331234567</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-1"></i> Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
