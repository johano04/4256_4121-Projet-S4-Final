<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\TypeOperationModel;
use App\Models\TrancheFraisModel;

class TranchesRetraitSeeder extends Seeder
{
    public function run()
    {
        $typeOperationModel = new TypeOperationModel();
        $trancheFraisModel  = new TrancheFraisModel();

        $typeRetrait = $typeOperationModel->trouverParCode('RETRAIT');

        if ($typeRetrait === null) {
            return;
        }

        $typeId = (int) $typeRetrait['id'];

        $trancheFraisModel->where('type_operation_id', $typeId)->delete();

        $tranches = [
            [100, 1000, 50],
            [1001, 5000, 50],
            [5001, 10000, 100],
            [10001, 25000, 200],
            [25001, 50000, 400],
            [50001, 100000, 800],
            [100001, 250000, 1500],
            [250001, 500000, 1500],
            [500001, 1000000, 2500],
            [1000001, 2000000, 3000],
        ];

        foreach ($tranches as [$min, $max, $frais]) {
            $trancheFraisModel->insert([
                'type_operation_id' => $typeId,
                'montant_min'       => $min,
                'montant_max'       => $max,
                'frais'             => $frais,
            ]);
        }
    }
}
