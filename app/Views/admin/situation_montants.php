<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<h1 class="h4 mb-4"><i class="bi bi-bar-chart-line me-2"></i>Situation des montants par opérateur</h1>

<div class="card shadow-sm">
    <div class="card-header"><i class="bi bi-diagram-3 me-2"></i>Total envoyé (transferts réussis) vers chaque opérateur</div>
    <div class="card-body p-0">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Opérateur</th>
                    <th>Nb transferts reçus</th>
                    <th>Montant total envoyé</th>
                    <th style="width: 40%">Répartition</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($montants as $m): ?>
                <?php $part = $totalEnvoye > 0 ? ((float) $m['total_envoye'] / $totalEnvoye) * 100 : 0; ?>
                <tr>
                    <td><strong><?= esc($m['nom_operateur']) ?></strong></td>
                    <td><?= (int) $m['nb_operations'] ?></td>
                    <td><?= number_format((float) $m['total_envoye'], 0, ',', ' ') ?> Ar</td>
                    <td>
                        <div class="progress" role="progressbar" style="height: 20px">
                            <div class="progress-bar bg-dark" style="width: <?= $part ?>%">
                                <?= number_format($part, 1) ?>%
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="2">Total</td>
                    <td><?= number_format($totalEnvoye, 0, ',', ' ') ?> Ar</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<p class="text-muted small mt-3">
    <i class="bi bi-info-circle me-1"></i>
    Exemple de lecture : si TELMA affiche 500 000 Ar, cela signifie que 500 000 Ar ont été envoyés par transfert
    vers des comptes clients rattachés à des préfixes TELMA.
</p>

<?= $this->endSection() ?>
