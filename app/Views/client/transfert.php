<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3"><i class="bi bi-arrow-left-right text-info me-2"></i>Faire un transfert</h1>
                <p class="text-muted">Solde actuel : <strong><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</strong></p>
                <p class="small text-muted"><i class="bi bi-info-circle me-1"></i>Des frais seront appliqués et débités de votre compte.</p>

                <form method="post" action="<?= site_url('client/transfert') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Numéro du destinataire</label>
                        <input type="text" name="telephone_destinataire" class="form-control" maxlength="10"
                               value="<?= old('telephone_destinataire') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant à transférer (Ar)</label>
                        <input type="number" name="montant" class="form-control" min="1" step="1"
                               value="<?= old('montant') ?>" required>
                    </div>
                    <button type="submit" class="btn btn-info text-white w-100"><i class="bi bi-check2 me-1"></i>Confirmer le transfert</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
