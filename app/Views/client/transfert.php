<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3"><i class="bi bi-arrow-left-right text-info me-2"></i>Faire un transfert</h1>
                <p class="text-muted">Solde actuel : <strong><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</strong></p>
                <p class="small text-muted"><i class="bi bi-info-circle me-1"></i>Des frais seront appliqués et débités de votre compte. Une commission supplémentaire s'ajoute si le destinataire est chez un autre opérateur.</p>

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
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="inclureFraisRetrait" name="inclure_frais_retrait" value="1"
                               <?= old('inclure_frais_retrait') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="inclureFraisRetrait">
                            Inclure les frais de retrait
                        </label>
                        <div class="form-text">Le destinataire pourra retirer ce montant sans payer de frais de retrait ; vous payez ce frais à sa place.</div>
                    </div>
                    <button type="submit" class="btn btn-info text-white w-100"><i class="bi bi-check2 me-1"></i>Confirmer le transfert</button>
                </form>

                <hr class="my-4">
                <a href="<?= site_url('client/transfert/multiple') ?>" class="btn btn-outline-info w-100">
                    <i class="bi bi-people me-1"></i>Envoyer à plusieurs numéros
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
