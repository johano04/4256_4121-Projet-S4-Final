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
<body class="auth-shell">

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
