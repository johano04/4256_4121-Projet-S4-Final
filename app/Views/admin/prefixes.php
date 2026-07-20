<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header"><i class="bi bi-plus-lg me-2"></i>Ajouter un préfixe</div>
            <div class="card-body">
                <form method="post" action="<?= site_url('admin/prefixes') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Préfixe (2 à 3 chiffres)</label>
                        <input type="text" name="prefixe" class="form-control" maxlength="3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opérateur</label>
                        <input type="text" name="operateur" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Ajouter</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header"><i class="bi bi-hash me-2"></i>Préfixes autorisés</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Préfixe</th>
                            <th>Opérateur</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($prefixes as $p): ?>
                        <tr>
                            <td><?= esc($p['prefixe']) ?></td>
                            <td><?= esc($p['operateur']) ?></td>
                            <td>
                                <span class="badge bg-<?= $p['actif'] ? 'success' : 'secondary' ?>">
                                    <?= $p['actif'] ? 'Actif' : 'Inactif' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <form method="post" action="<?= site_url('admin/prefixes/' . $p['id'] . '/basculer') ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-secondary"><?= $p['actif'] ? 'Désactiver' : 'Activer' ?></button>
                                </form>
                                <form method="post" action="<?= site_url('admin/prefixes/' . $p['id'] . '/supprimer') ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
