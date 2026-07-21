<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center w-100">
    <div class="col-md-5">
        <div class="auth-brand">
            <span class="auth-brand-icon"><i class="bi bi-wallet2"></i></span>
            <span class="brand-word">VolaDigital</span>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h5 mb-1 text-center">Bienvenue</h1>
                <p class="text-muted text-center small mb-4">Entrez votre numéro de téléphone pour accéder à votre compte.</p>

                <form method="post" action="<?= site_url('connexion') ?>" id="formConnexion" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Numéro de téléphone</label>
                        <input type="text" name="telephone" id="telephone" class="form-control" placeholder="038......"
                               value="<?= old('telephone') ?>" maxlength="10" required>
                        <div class="form-text">Format : 10 chiffres, réservé aux numéros MVola (038...)</div>
                        <div id="erreurTelephone" class="text-danger small mt-1 d-none"></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-1"></i> Se connecter</button>
                </form>

                <script>
                    (function () {
                        // Doit rester synchronisé avec AuthController::PREFIXES_AUTORISES (validation définitive côté serveur).
                        var prefixesAutorises = <?= json_encode($prefixesAutorises ?? ['038']) ?>;

                        var form           = document.getElementById('formConnexion');
                        var champTelephone = document.getElementById('telephone');
                        var champErreur    = document.getElementById('erreurTelephone');

                        function prefixeValide(numero) {
                            return prefixesAutorises.some(function (prefixe) {
                                return numero.startsWith(prefixe);
                            });
                        }

                        function afficherErreur(message) {
                            champErreur.textContent = message;
                            champErreur.classList.remove('d-none');
                            champTelephone.classList.add('is-invalid');
                        }

                        function masquerErreur() {
                            champErreur.classList.add('d-none');
                            champTelephone.classList.remove('is-invalid');
                        }

                        form.addEventListener('submit', function (event) {
                            var numero = champTelephone.value.trim();

                            if (! /^[0-9]{10}$/.test(numero)) {
                                event.preventDefault();
                                afficherErreur('Le numéro doit contenir exactement 10 chiffres.');
                                return;
                            }

                            if (! prefixeValide(numero)) {
                                event.preventDefault();
                                afficherErreur('Seuls les numéros MVola (préfixe ' + prefixesAutorises.join(', ') + ') sont acceptés.');
                                return;
                            }

                            masquerErreur();
                        });

                        champTelephone.addEventListener('input', masquerErreur);
                    })();
                </script>

                <div class="text-center mt-3">
                    <a href="<?= site_url('admin/connexion') ?>" class="small text-muted">
                        <i class="bi bi-shield-lock me-1"></i>Espace administrateur
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
