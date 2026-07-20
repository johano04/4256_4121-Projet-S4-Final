<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <h6 class="card-subtitle mb-2"><i class="bi bi-wallet2 me-1"></i>Solde disponible</h6>
                <h2 class="card-title"><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</h2>
                <p class="mb-0 opacity-75">Numéro : <?= esc($client['telephone']) ?></p>
            </div>
        </div>

        <div class="d-grid gap-2 mt-3">
            <a href="<?= site_url('client/depot') ?>" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i>Faire un dépôt</a>
            <a href="<?= site_url('client/retrait') ?>" class="btn btn-warning"><i class="bi bi-dash-circle me-1"></i>Faire un retrait</a>
            <a href="<?= site_url('client/transfert') ?>" class="btn btn-info text-white"><i class="bi bi-arrow-left-right me-1"></i>Faire un transfert</a>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-ul me-2"></i>Dernières opérations</span>
                <a href="<?= site_url('client/historique') ?>" class="small">Voir tout l'historique <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($operations)): ?>
                    <p class="text-muted p-3 mb-0">Aucune opération pour le moment.</p>
                <?php else: ?>
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Montant</th>
                                <th>Frais</th>
                                <th>Solde après</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($operations as $op): ?>
                            <tr>
                                <td><?= esc($op['created_at']) ?></td>
                                <td><?= esc($op['type_libelle']) ?></td>
                                <td><?= number_format($op['montant'], 0, ',', ' ') ?> Ar</td>
                                <td><?= number_format($op['frais'], 0, ',', ' ') ?> Ar</td>
                                <td><?= number_format($op['solde_apres'], 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
