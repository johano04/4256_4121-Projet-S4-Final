<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<div class="card shadow-sm">
    <div class="card-header"><i class="bi bi-clock-history me-2"></i>Historique complet des opérations</div>
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
                        <th>Commission inter-op.</th>
                        <th>Solde avant</th>
                        <th>Solde après</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($operations as $op): ?>
                    <tr>
                        <td>
                            <?= esc($op['created_at']) ?>
                            <?php if (! empty($op['reference_groupe'])): ?>
                                <span class="badge bg-secondary" title="Envoi multiple"><i class="bi bi-people"></i> lot</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($op['type_libelle']) ?></td>
                        <td><?= number_format($op['montant'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($op['frais'], 0, ',', ' ') ?> Ar</td>
                        <td>
                            <?php if (! empty($op['est_inter_operateur'])): ?>
                                <?= number_format($op['commission_supplementaire'], 0, ',', ' ') ?> Ar
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= number_format($op['solde_avant'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($op['solde_apres'], 0, ',', ' ') ?> Ar</td>
                        <td>
                            <span class="badge bg-<?= $op['statut'] === 'REUSSI' ? 'success' : 'danger' ?>">
                                <?= esc($op['statut']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
