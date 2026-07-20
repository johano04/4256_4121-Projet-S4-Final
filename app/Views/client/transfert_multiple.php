<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3"><i class="bi bi-people text-info me-2"></i>Envoi multiple</h1>
                <p class="text-muted">Solde actuel : <strong><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</strong></p>
                <p class="small text-muted"><i class="bi bi-info-circle me-1"></i>
                    Envoyez de l'argent à plusieurs numéros en une seule fois : soit en divisant un montant total,
                    soit avec un montant personnalisé par numéro.
                </p>

                <form method="post" action="<?= site_url('client/transfert/multiple') ?>" id="formEnvoiMultiple">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label d-block">Mode de répartition</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="mode" id="modeDivision" value="division" checked>
                            <label class="btn btn-outline-info" for="modeDivision">Division automatique</label>

                            <input type="radio" class="btn-check" name="mode" id="modePersonnalise" value="personnalise">
                            <label class="btn btn-outline-info" for="modePersonnalise">Montant personnalisé</label>
                        </div>
                    </div>

                    <div class="mb-3" id="blocMontantTotal">
                        <label class="form-label">Montant total à répartir (Ar)</label>
                        <input type="number" name="montant_total" id="montantTotal" class="form-control" min="1" step="1"
                               value="<?= old('montant_total') ?>">
                        <div class="form-text">
                            Ex : 30 000 Ar pour 3 numéros = 10 000 Ar chacun (le reliquat éventuel est ajouté au dernier destinataire).
                        </div>
                    </div>

                    <div id="listeNumeros">
                        <div class="row g-2 mb-2 ligne-destinataire">
                            <div class="col-7">
                                <input type="text" name="numeros[]" class="form-control" placeholder="Numéro du destinataire" maxlength="10">
                            </div>
                            <div class="col-4 champ-montant-personnalise d-none">
                                <input type="number" name="montants[]" class="form-control" placeholder="Montant (Ar)" min="1" step="1">
                            </div>
                            <div class="col-1">
                                <button type="button" class="btn btn-outline-danger btn-supprimer-ligne">&times;</button>
                            </div>
                        </div>
                        <div class="row g-2 mb-2 ligne-destinataire">
                            <div class="col-7">
                                <input type="text" name="numeros[]" class="form-control" placeholder="Numéro du destinataire" maxlength="10">
                            </div>
                            <div class="col-4 champ-montant-personnalise d-none">
                                <input type="number" name="montants[]" class="form-control" placeholder="Montant (Ar)" min="1" step="1">
                            </div>
                            <div class="col-1">
                                <button type="button" class="btn btn-outline-danger btn-supprimer-ligne">&times;</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-secondary mb-3" id="btnAjouterLigne">
                        <i class="bi bi-plus-lg me-1"></i>Ajouter un numéro
                    </button>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="inclureFraisRetraitMultiple" name="inclure_frais_retrait" value="1"
                               <?= old('inclure_frais_retrait') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="inclureFraisRetraitMultiple">Inclure les frais de retrait pour chaque destinataire</label>
                    </div>

                    <button type="submit" class="btn btn-info text-white w-100"><i class="bi bi-check2 me-1"></i>Envoyer</button>
                </form>

                <a href="<?= site_url('client/transfert') ?>" class="d-block text-center mt-3">
                    <i class="bi bi-arrow-left me-1"></i>Retour au transfert simple
                </a>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const listeNumeros   = document.getElementById('listeNumeros');
    const btnAjouter      = document.getElementById('btnAjouterLigne');
    const radiosMode      = document.querySelectorAll('input[name="mode"]');
    const blocMontantTotal = document.getElementById('blocMontantTotal');

    function nouvelleLigne() {
        const ligne = document.createElement('div');
        ligne.className = 'row g-2 mb-2 ligne-destinataire';
        ligne.innerHTML = `
            <div class="col-7">
                <input type="text" name="numeros[]" class="form-control" placeholder="Numéro du destinataire" maxlength="10">
            </div>
            <div class="col-4 champ-montant-personnalise d-none">
                <input type="number" name="montants[]" class="form-control" placeholder="Montant (Ar)" min="1" step="1">
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-outline-danger btn-supprimer-ligne">&times;</button>
            </div>
        `;
        listeNumeros.appendChild(ligne);
        appliquerMode();
    }

    function appliquerMode() {
        const modePersonnalise = document.getElementById('modePersonnalise').checked;
        document.querySelectorAll('.champ-montant-personnalise').forEach(function (champ) {
            champ.classList.toggle('d-none', ! modePersonnalise);
        });
        blocMontantTotal.classList.toggle('d-none', modePersonnalise);
    }

    btnAjouter.addEventListener('click', nouvelleLigne);

    listeNumeros.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-supprimer-ligne')) {
            const lignes = listeNumeros.querySelectorAll('.ligne-destinataire');
            if (lignes.length > 1) {
                e.target.closest('.ligne-destinataire').remove();
            }
        }
    });

    radiosMode.forEach(function (radio) {
        radio.addEventListener('change', appliquerMode);
    });

    appliquerMode();
})();
</script>

<?= $this->endSection() ?>
