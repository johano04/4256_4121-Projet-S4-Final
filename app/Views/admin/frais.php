<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header">Ajouter une tranche de frais</div>
            <div class="card-body">
                <form method="post" action="<?= site_url('admin/frais') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Type d'opération</label>
                        <select name="type_operation_id" class="form-select" required>
                            <?php foreach ($types as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= esc($t['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant minimum (Ar)</label>
                        <input type="number" name="montant_min" class="form-control" min="0" step="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant maximum (Ar)</label>
                        <input type="number" name="montant_max" class="form-control" min="0" step="1">
                        <div class="form-text">Laisser vide = pas de limite supérieure</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Frais appliqué (Ar)</label>
                        <input type="number" name="frais" class="form-control" min="0" step="1" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Ajouter la tranche</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <?php foreach ($types as $t): ?>
            <div class="card shadow-sm mb-3">
                <div class="card-header"><?= esc($t['libelle']) ?></div>
                <div class="card-body p-0">
                    <?php if (empty($t['tranches'])): ?>
                        <p class="text-muted p-3 mb-0">Aucune tranche définie.</p>
                    <?php else: ?>
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Montant min</th>
                                    <th>Montant max</th>
                                    <th>Frais</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($t['tranches'] as $tr): ?>
                                <tr>
                                    <td><?= number_format((float) $tr['montant_min'], 0, ',', ' ') ?> Ar</td>
                                    <td><?= $tr['montant_max'] === null ? 'Sans limite' : number_format((float) $tr['montant_max'], 0, ',', ' ') . ' Ar' ?></td>
                                    <td><?= number_format((float) $tr['frais'], 0, ',', ' ') ?> Ar</td>
                                    <td class="text-end">
                                        <form method="post" action="<?= site_url('admin/frais/' . $tr['id'] . '/supprimer') ?>">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>
