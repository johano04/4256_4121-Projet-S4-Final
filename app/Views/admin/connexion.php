<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow-sm mt-5">
            <div class="card-body">
                <h1 class="h4 mb-3 text-center">Connexion administrateur</h1>

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
                    <button type="submit" class="btn btn-dark w-100">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
