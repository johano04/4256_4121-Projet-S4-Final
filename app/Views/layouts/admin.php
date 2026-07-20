<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?? 'Administration - VolaDigital' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="<?= session()->get('is_admin') ? 'app-shell' : 'auth-shell' ?>">

<?php if (session()->get('is_admin')): ?>
<nav class="navbar navbar-expand-lg navbar-app-admin mb-4">
    <div class="container">
        <div class="d-flex align-items-center flex-row flex-wrap">
            <a class="navbar-brand" href="<?= site_url('admin') ?>">
                <span class="brand-badge"><i class="bi bi-shield-lock"></i></span>
                VolaDigital <span class="d-none d-sm-inline">· Admin</span>
            </a>
            <div class="navbar-nav flex-row flex-wrap">
                <a class="nav-link<?= current_url() === site_url('admin') ? ' active' : '' ?>" href="<?= site_url('admin') ?>"><i class="bi bi-speedometer2"></i><span class="nav-label">Tableau de bord</span></a>
                <a class="nav-link<?= current_url() === site_url('admin/prefixes') ? ' active' : '' ?>" href="<?= site_url('admin/prefixes') ?>"><i class="bi bi-hash"></i><span class="nav-label">Préfixes</span></a>
                <a class="nav-link<?= current_url() === site_url('admin/operateurs') ? ' active' : '' ?>" href="<?= site_url('admin/operateurs') ?>"><i class="bi bi-diagram-3"></i><span class="nav-label">Opérateurs</span></a>
                <a class="nav-link<?= current_url() === site_url('admin/types-operation') ? ' active' : '' ?>" href="<?= site_url('admin/types-operation') ?>"><i class="bi bi-tags"></i><span class="nav-label">Types d'opération</span></a>
                <a class="nav-link<?= current_url() === site_url('admin/frais') ? ' active' : '' ?>" href="<?= site_url('admin/frais') ?>"><i class="bi bi-percent"></i><span class="nav-label">Frais</span></a>
                <a class="nav-link<?= current_url() === site_url('admin/rapports/gains') ? ' active' : '' ?>" href="<?= site_url('admin/rapports/gains') ?>"><i class="bi bi-graph-up-arrow"></i><span class="nav-label">Situation gains</span></a>
                <a class="nav-link<?= current_url() === site_url('admin/rapports/montants') ? ' active' : '' ?>" href="<?= site_url('admin/rapports/montants') ?>"><i class="bi bi-bar-chart-line"></i><span class="nav-label">Montants par opérateur</span></a>
            </div>
            <form method="post" action="<?= site_url('admin/deconnexion') ?>" class="m-0">
                <?= csrf_field() ?>
                <button class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i> Deconexion</button>
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
    <?php if (session()->getFlashdata('erreurs')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('erreurs') as $erreur): ?>
                    <li><?= esc($erreur) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
