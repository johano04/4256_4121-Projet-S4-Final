<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<h1 class="h4 mb-4"><i class="bi bi-graph-up-arrow me-2"></i>Situation gain via les différents frais</h1>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body">
                <h6 class="card-subtitle mb-2"><i class="bi bi-percent me-1"></i>Total commissions normales</h6>
                <h2 class="card-title"><?= number_format($totalCommissionNormale, 0, ',', ' ') ?> Ar</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning shadow-sm">
            <div class="card-body">
                <h6 class="card-subtitle mb-2"><i class="bi bi-globe me-1"></i>Total commissions supplémentaires (inter-opérateur)</h6>
                <h2 class="card-title"><?= number_format($totalCommissionSupplementaire, 0, ',', ' ') ?> Ar</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-dark shadow-sm">
            <div class="card-body">
                <h6 class="card-subtitle mb-2"><i class="bi bi-cash-stack me-1"></i>Total général des gains (transferts)</h6>
                <h2 class="card-title"><?= number_format($totalGeneral, 0, ',', ' ') ?> Ar</h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header"><i class="bi bi-table me-2"></i>Détail intra-opérateur vs inter-opérateur</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Catégorie de transfert</th>
                    <th>Nb opérations</th>
                    <th>Commission normale</th>
                    <th>Commission supplémentaire</th>
                    <th>Total gains</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="badge bg-secondary">Intra-opérateur</span> <span class="text-muted small">(même opérateur émetteur/destinataire)</span></td>
                    <td><?= (int) $intra['nb_operations'] ?></td>
                    <td><?= number_format((float) $intra['total_commission_normale'], 0, ',', ' ') ?> Ar</td>
                    <td>0 Ar</td>
                    <td><?= number_format((float) $intra['total_gains'], 0, ',', ' ') ?> Ar</td>
                </tr>
                <tr>
                    <td><span class="badge bg-info text-dark">Inter-opérateur</span> <span class="text-muted small">(opérateurs différents)</span></td>
                    <td><?= (int) $inter['nb_operations'] ?></td>
                    <td><?= number_format((float) $inter['total_commission_normale'], 0, ',', ' ') ?> Ar</td>
                    <td><?= number_format((float) $inter['total_commission_supplementaire'], 0, ',', ' ') ?> Ar</td>
                    <td><?= number_format((float) $inter['total_gains'], 0, ',', ' ') ?> Ar</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td>Total</td>
                    <td><?= (int) $intra['nb_operations'] + (int) $inter['nb_operations'] ?></td>
                    <td><?= number_format($totalCommissionNormale, 0, ',', ' ') ?> Ar</td>
                    <td><?= number_format($totalCommissionSupplementaire, 0, ',', ' ') ?> Ar</td>
                    <td><?= number_format($totalGeneral, 0, ',', ' ') ?> Ar</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<p class="text-muted small mt-3">
    <i class="bi bi-info-circle me-1"></i>
    La commission normale correspond au frais par tranche (identique pour tous les transferts).
    La commission supplémentaire n'est prélevée que lorsque le transfert change d'opérateur, selon le taux configuré dans
    <a href="<?= site_url('admin/operateurs') ?>">Opérateurs</a>.
</p>

<?= $this->endSection() ?>
