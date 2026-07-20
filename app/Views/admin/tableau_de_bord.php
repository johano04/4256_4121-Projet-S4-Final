<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Gains totaux (frais)</h6>
                <h2 class="card-title"><?= number_format($totalFrais, 0, ',', ' ') ?> Ar</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Nombre de clients</h6>
                <h2 class="card-title"><?= $nombreClients ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info shadow-sm">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Total des soldes clients</h6>
                <h2 class="card-title"><?= number_format($totalSoldeClients, 0, ',', ' ') ?> Ar</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header">Gains par type d'opération</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Nb opérations</th>
                            <th>Total frais</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($gainsParType as $g): ?>
                        <tr>
                            <td><?= esc($g['type_libelle']) ?></td>
                            <td><?= (int) $g['nb_operations'] ?></td>
                            <td><?= number_format((float) $g['total_frais'], 0, ',', ' ') ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header">Situation globale des comptes clients</div>
            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Téléphone</th>
                            <th>Nom</th>
                            <th>Préfixe</th>
                            <th>Solde</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($comptes as $c): ?>
                        <tr>
                            <td><?= esc($c['telephone']) ?></td>
                            <td><?= esc($c['nom'] ?? '-') ?></td>
                            <td><?= esc($c['prefixe']) ?></td>
                            <td><?= number_format((float) $c['solde'], 0, ',', ' ') ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
