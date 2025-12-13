<?php

namespace Database\Seeders;

use App\Models\Prosecutor;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProsecutorSeeder extends Seeder
{
    public function run(): void
    {
        $prosecutors = [
            [
                'user_id' => 1,
                'bar_number' => 'BAR001',
                'court_division' => 'Criminal Division',
                'office_location' => 'Downtown Courthouse - Room 201',
            ],
            [
                'user_id' => null,
                'bar_number' => 'BAR002',
                'court_division' => 'Cyber Crime Division',
                'office_location' => 'Tech Hub - Floor 3',
            ],
            [
                'user_id' => null,
                'bar_number' => 'BAR003',
                'court_division' => 'Financial Crimes Division',
                'office_location' => 'Financial District - Tower B',
            ],
            [
                'user_id' => null,
                'bar_number' => 'BAR004',
                'court_division' => 'Organized Crime Division',
                'office_location' => 'Central Station - Basement 2',
            ],
        ];

        foreach ($prosecutors as $prosecutor) {
            Prosecutor::create($prosecutor);
        }
    }
}
