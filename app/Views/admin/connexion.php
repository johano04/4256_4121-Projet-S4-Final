<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center w-100">
    <div class="col-md-4">
        <div class="auth-brand">
            <span class="auth-brand-icon"><i class="bi bi-shield-lock"></i></span>
            <span class="brand-word">Administration</span>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h5 mb-3 text-center">Connexion administrateur</h1>

                <form method="post" action="<?= site_url('admin/connexion') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Utilisateur</label>
                        <input type="text" name="utilisateur" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="mot_de_passe" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100"><i class="bi bi-box-arrow-in-right me-1"></i> Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
