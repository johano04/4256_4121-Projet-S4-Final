<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<h1 class="h4 mb-4"><i class="bi bi-journal-text me-2"></i>Journal des transactions</h1>

<div class="card shadow-sm">
    <div class="card-header"><i class="bi bi-wallet2 me-2"></i>Toutes les opérations initiées par un client MVola</div>
    <div class="card-body p-0">
        <?php if (empty($operations)): ?>
            <p class="text-muted p-3 mb-0">Aucune transaction pour le moment.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Expéditeur (MVola)</th>
                            <th>Destinataire</th>
                            <th>Montant</th>
                            <th>Frais</th>
                            <th>Commission inter-op.</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($operations as $op): ?>
                        <?php $destinataireMembre = $op['destinataire_telephone_membre'] ?? null; ?>
                        <tr>
                            <td>
                                <?= esc($op['created_at']) ?>
                                <?php if (! empty($op['reference_groupe'])): ?>
                                    <span class="badge bg-secondary" title="Envoi multiple"><i class="bi bi-people"></i> lot</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($op['type_libelle']) ?></td>
                            <td><?= esc($op['expediteur_telephone']) ?></td>
                            <td>
                                <?php if ($destinataireMembre !== null): ?>
                                    <?= esc($destinataireMembre) ?> <span class="badge bg-dark">MVola</span>
                                <?php elseif (! empty($op['telephone_destinataire'])): ?>
                                    <?= esc($op['telephone_destinataire']) ?> <span class="badge bg-warning text-dark">Externe</span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= number_format($op['montant'], 0, ',', ' ') ?> Ar</td>
                            <td><?= number_format($op['frais'], 0, ',', ' ') ?> Ar</td>
                            <td>
                                <?php if (! empty($op['est_inter_operateur'])): ?>
                                    <?= number_format($op['commission_supplementaire'], 0, ',', ' ') ?> Ar
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $op['statut'] === 'REUSSI' ? 'success' : 'danger' ?>">
                                    <?= esc($op['statut']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<p class="text-muted small mt-3">
    <i class="bi bi-info-circle me-1"></i>
    Un destinataire "Externe" n'a pas de compte dans cette base : le numéro appartient à un autre opérateur
    que celui autorisé (MVola), seule la transaction est tracée ici, aucun solde n'est créé ni crédité pour lui.
</p>

<?= $this->endSection() ?>
