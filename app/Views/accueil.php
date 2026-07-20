<?= $this->extend('layouts/accueil') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <span class="auth-brand-icon mb-2"><i class="bi bi-wallet2"></i></span>
        <h1 class="h3 mb-1">Mobile Money</h1>
        <p class="text-muted">Choisissez votre espace pour accéder à l'application.</p>
    </div>

    <div class="row justify-content-center g-4">
        <div class="col-md-5">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
                <div class="mb-3">
                    <span class="auth-brand-icon bg-primary text-white"><i class="bi bi-person-circle"></i></span>
                </div>
                <h2 class="h5">Espace Client</h2>
                <p class="text-muted small">Consultez votre solde, effectuez des dépôts, retraits et transferts, et suivez votre historique.</p>
                <a href="<?= site_url('connexion') ?>" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Accéder à l'espace client
                </a>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
                <div class="mb-3">
                    <span class="auth-brand-icon bg-dark text-white"><i class="bi bi-shield-lock"></i></span>
                </div>
                <h2 class="h5">Espace Administrateur</h2>
                <p class="text-muted small">Gérez les préfixes, les types d'opérations, les barèmes de frais, les gains et les comptes clients.</p>
                <a href="<?= site_url('admin/connexion') ?>" class="btn btn-dark w-100">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Accéder à l'espace admin
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
