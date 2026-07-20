<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm mt-5">
            <div class="card-body">
                <h1 class="h4 mb-3 text-center">Mobile Money</h1>
                <p class="text-muted text-center">Entrez votre numéro de téléphone pour accéder à votre compte.</p>

                <form method="post" action="<?= site_url('connexion') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Numéro de téléphone</label>
                        <input type="text" name="telephone" class="form-control" placeholder="0331234567"
                               value="<?= old('telephone') ?>" maxlength="10" required>
                        <div class="form-text">Format : 10 chiffres, ex. 0331234567</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
