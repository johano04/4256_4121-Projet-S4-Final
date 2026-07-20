<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?? 'Mobile Money' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="<?= session()->get('client_id') ? 'app-shell' : 'auth-shell' ?>">

<?php if (session()->get('client_id')): ?>
<nav class="navbar navbar-expand-lg navbar-app mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('client/tableau-de-bord') ?>">
            <span class="brand-badge"><i class="bi bi-wallet2"></i></span>
            Mobile Money
        </a>
        <div class="navbar-nav flex-row flex-wrap">
            <a class="nav-link<?= current_url() === site_url('client/tableau-de-bord') ? ' active' : '' ?>" href="<?= site_url('client/tableau-de-bord') ?>"><i class="bi bi-speedometer2"></i><span class="nav-label">Tableau de bord</span></a>
            <a class="nav-link<?= current_url() === site_url('client/depot') ? ' active' : '' ?>" href="<?= site_url('client/depot') ?>"><i class="bi bi-plus-circle"></i><span class="nav-label">Dépôt</span></a>
            <a class="nav-link<?= current_url() === site_url('client/retrait') ? ' active' : '' ?>" href="<?= site_url('client/retrait') ?>"><i class="bi bi-dash-circle"></i><span class="nav-label">Retrait</span></a>
            <a class="nav-link<?= current_url() === site_url('client/transfert') ? ' active' : '' ?>" href="<?= site_url('client/transfert') ?>"><i class="bi bi-arrow-left-right"></i><span class="nav-label">Transfert</span></a>
            <a class="nav-link<?= current_url() === site_url('client/historique') ? ' active' : '' ?>" href="<?= site_url('client/historique') ?>"><i class="bi bi-clock-history"></i><span class="nav-label">Historique</span></a>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="badge-phone d-none d-sm-inline"><?= esc(session()->get('client_telephone')) ?></span>
            <form method="post" action="<?= site_url('deconnexion') ?>" class="m-0">
                <?= csrf_field() ?>
                <button class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i></button>
            </form>
        </div>
    </div>
</nav>
<?php endif; ?>

<div class="container">
    <?php if (session()->getFlashdata('succes')): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('succes')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('erreur')): ?>
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('erreur')) ?></div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>

</body>
</html>
