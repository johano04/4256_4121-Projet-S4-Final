<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?? 'VolaDigital' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="<?= session()->get('client_id') ? 'app-shell' : 'auth-shell' ?>">

<?php if (session()->get('client_id')): ?>
<div class="d-flex layout-with-sidebar">
    <div class="offcanvas-md offcanvas-start sidebar-app" tabindex="-1" id="sidebarClient" aria-labelledby="sidebarClientLabel">
        <div class="offcanvas-header">
            <a class="sidebar-brand" href="<?= site_url('client/tableau-de-bord') ?>" id="sidebarClientLabel">
                <span class="brand-badge"><i class="bi bi-wallet2"></i></span>
                VolaDigital
            </a>
            <button type="button" class="btn-close btn-close-white d-md-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebarClient" aria-label="Fermer"></button>
        </div>
        <div class="offcanvas-body sidebar-body">
            <nav class="nav flex-column sidebar-nav">
                <a class="nav-link<?= current_url() === site_url('client/tableau-de-bord') ? ' active' : '' ?>" href="<?= site_url('client/tableau-de-bord') ?>"><i class="bi bi-speedometer2"></i><span>Tableau de bord</span></a>
                <a class="nav-link<?= current_url() === site_url('client/depot') ? ' active' : '' ?>" href="<?= site_url('client/depot') ?>"><i class="bi bi-plus-circle"></i><span>Dépôt</span></a>
                <a class="nav-link<?= current_url() === site_url('client/retrait') ? ' active' : '' ?>" href="<?= site_url('client/retrait') ?>"><i class="bi bi-dash-circle"></i><span>Retrait</span></a>
                <a class="nav-link<?= current_url() === site_url('client/transfert') ? ' active' : '' ?>" href="<?= site_url('client/transfert') ?>"><i class="bi bi-arrow-left-right"></i><span>Transfert</span></a>
                <a class="nav-link<?= current_url() === site_url('client/transfert/multiple') ? ' active' : '' ?>" href="<?= site_url('client/transfert/multiple') ?>"><i class="bi bi-people"></i><span>Envoi multiple</span></a>
                <a class="nav-link<?= current_url() === site_url('client/historique') ? ' active' : '' ?>" href="<?= site_url('client/historique') ?>"><i class="bi bi-clock-history"></i><span>Historique</span></a>
            </nav>
            <div class="sidebar-footer">
                <span class="badge-phone d-block text-center mb-2"><?= esc(session()->get('client_telephone')) ?></span>
                <form method="post" action="<?= site_url('deconnexion') ?>" class="m-0">
                    <?= csrf_field() ?>
                    <button class="btn btn-outline-light btn-sm w-100"><i class="bi bi-box-arrow-right"></i> Deconnexion</button>
                </form>
            </div>
        </div>
    </div>

    <div class="flex-grow-1 main-content">
        <nav class="navbar navbar-app d-md-none mb-3">
            <div class="container-fluid">
                <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarClient" aria-controls="sidebarClient">
                    <i class="bi bi-list"></i>
                </button>
                <span class="navbar-brand mb-0"><i class="bi bi-wallet2 me-1"></i>VolaDigital</span>
                <span class="badge-phone"><?= esc(session()->get('client_telephone')) ?></span>
            </div>
        </nav>

        <div class="container">
            <?php if (session()->getFlashdata('succes')): ?>
                <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('succes')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('erreur')): ?>
                <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('erreur')) ?></div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="container">
    <?php if (session()->getFlashdata('succes')): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('succes')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('erreur')): ?>
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('erreur')) ?></div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
