<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3"><i class="bi bi-plus-circle text-success me-2"></i>Faire un dépôt</h1>
                <p class="text-muted">Solde actuel : <strong><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</strong></p>

                <form method="post" action="<?= site_url('client/depot') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Montant à déposer (Ar)</label>
                        <input type="number" name="montant" class="form-control" min="1" step="1"
                               value="<?= old('montant') ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-check2 me-1"></i>Confirmer le dépôt</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
