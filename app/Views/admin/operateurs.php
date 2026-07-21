<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header"><i class="bi bi-plus-lg me-2"></i>Ajouter un opérateur</div>
            <div class="card-body">
                <form method="post" action="<?= site_url('admin/operateurs') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nom de l'opérateur</label>
                        <input type="text" name="nom_operateur" class="form-control" placeholder="ex: Telma" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Commission inter-opérateur (%)</label>
                        <input type="number" name="commission_inter_operateur" class="form-control" min="0" step="0.01" value="2.00" required>
                        <div class="form-text">Surcoût appliqué en plus des frais normaux quand un transfert sort vers un autre opérateur.</div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Ajouter</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header"><i class="bi bi-diagram-3 me-2"></i>Opérateurs configurés</div>
            <div class="card-body p-0">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Opérateur</th>
                            <th>Commission inter-opérateur</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($operateurs as $o): ?>
                        <tr>
                            <td><?= esc($o['nom_operateur']) ?></td>
                            <td><?= number_format((float) $o['commission_inter_operateur'], '', '', '') ?> %</td>
                            <td class="text-end">
                                <form method="post" action="<?= site_url('admin/operateurs/' . $o['id'] . '/commission') ?>" class="d-flex justify-content-end gap-2">
                                    <?= csrf_field() ?>
                                    <input type="number" name="commission_inter_operateur" class="form-control form-control-sm" style="width: 100px"
                                           min="0" step="0.01" value="<?= (float) $o['commission_inter_operateur'] ?>" required>
                                    <button class="btn btn-sm btn-outline-secondary">Enregistrer</button>
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
