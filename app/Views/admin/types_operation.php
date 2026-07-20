<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header">Ajouter un type d'opération</div>
            <div class="card-body">
                <form method="post" action="<?= site_url('admin/types-operation') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Code (ex: DEPOT)</label>
                        <input type="text" name="code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Libellé (ex: Dépôt)</label>
                        <input type="text" name="libelle" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Ajouter</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header">Types d'opération</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Libellé</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($types as $t): ?>
                        <tr>
                            <td><?= esc($t['code']) ?></td>
                            <td><?= esc($t['libelle']) ?></td>
                            <td>
                                <span class="badge bg-<?= $t['actif'] ? 'success' : 'secondary' ?>">
                                    <?= $t['actif'] ? 'Actif' : 'Inactif' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <form method="post" action="<?= site_url('admin/types-operation/' . $t['id'] . '/basculer') ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-secondary"><?= $t['actif'] ? 'Désactiver' : 'Activer' ?></button>
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
