<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Solde disponible</h6>
                <h2 class="card-title"><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</h2>
                <p class="mb-0">Numéro : <?= esc($client['telephone']) ?></p>
            </div>
        </div>

        <div class="d-grid gap-2 mt-3">
            <a href="<?= site_url('client/depot') ?>" class="btn btn-success">Faire un dépôt</a>
            <a href="<?= site_url('client/retrait') ?>" class="btn btn-warning">Faire un retrait</a>
            <a href="<?= site_url('client/transfert') ?>" class="btn btn-info text-white">Faire un transfert</a>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Dernières opérations</span>
                <a href="<?= site_url('client/historique') ?>" class="small">Voir tout l'historique</a>
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
