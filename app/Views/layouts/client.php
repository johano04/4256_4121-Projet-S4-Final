<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?? 'Mobile Money' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php if (session()->get('client_id')): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('client/tableau-de-bord') ?>">Mobile Money</a>
        <div class="navbar-nav">
            <a class="nav-link" href="<?= site_url('client/tableau-de-bord') ?>">Tableau de bord</a>
            <a class="nav-link" href="<?= site_url('client/depot') ?>">Dépôt</a>
            <a class="nav-link" href="<?= site_url('client/retrait') ?>">Retrait</a>
            <a class="nav-link" href="<?= site_url('client/transfert') ?>">Transfert</a>
            <a class="nav-link" href="<?= site_url('client/historique') ?>">Historique</a>
        </div>
        <div class="ms-auto d-flex align-items-center">
            <span class="text-white me-3"><?= esc(session()->get('client_telephone')) ?></span>
            <form method="post" action="<?= site_url('deconnexion') ?>" class="m-0">
                <?= csrf_field() ?>
                <button class="btn btn-outline-light btn-sm">Déconnexion</button>
            </form>
        </div>
    </div>
</nav>
<?php endif; ?>

<div class="container">
    <?php if (session()->getFlashdata('succes')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('succes')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('erreur')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('erreur')) ?></div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>

</body>
</html>
