<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?? 'Administration - Mobile Money' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php if (session()->get('is_admin')): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('admin') ?>">Mobile Money - Admin</a>
        <div class="navbar-nav">
            <a class="nav-link" href="<?= site_url('admin') ?>">Tableau de bord</a>
            <a class="nav-link" href="<?= site_url('admin/prefixes') ?>">Préfixes</a>
            <a class="nav-link" href="<?= site_url('admin/types-operation') ?>">Types d'opération</a>
            <a class="nav-link" href="<?= site_url('admin/frais') ?>">Frais</a>
        </div>
        <div class="ms-auto">
            <form method="post" action="<?= site_url('admin/deconnexion') ?>" class="m-0">
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

</body>
</html>
