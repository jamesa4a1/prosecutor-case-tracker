<?php

namespace Database\Seeders;

use App\Models\Hearing;
use Illuminate\Database\Seeder;

class HearingSeeder extends Seeder
{
    public function run(): void
    {
        $hearings = [
            [
                'case_id' => 1,
                'date' => now()->addDays(7),
                'time' => '09:00:00',
                'location' => 'Room 101 - District Court',
                'type' => 'Pre-trial Conference',
                'outcome' => null,
                'notes' => 'Preliminary hearing to discuss evidence and procedures.',
            ],
            [
                'case_id' => 1,
                'date' => now()->addDays(30),
                'time' => '10:30:00',
                'location' => 'Room 105 - District Court',
                'type' => 'Arraignment',
                'outcome' => null,
                'notes' => 'Defendant to enter plea.',
            ],
            [
                'case_id' => 2,
                'date' => now()->addDays(14),
                'time' => '14:00:00',
                'location' => 'Room 201 - Superior Court',
                'type' => 'Motion Hearing',
                'outcome' => null,
                'notes' => 'Hearing on suppression of evidence motion.',
            ],
            [
                'case_id' => 3,
                'date' => now()->subDays(5),
                'time' => '09:00:00',
                'location' => 'Room 103 - District Court',
                'type' => 'Preliminary Hearing',
                'outcome' => 'Case bound over for trial',
                'notes' => 'Probable cause established.',
            ],
            [
                'case_id' => 4,
                'date' => now()->addDays(21),
                'time' => '11:00:00',
                'location' => 'Room 305 - Cyber Court',
                'type' => 'Pre-trial Conference',
                'outcome' => null,
                'notes' => 'Technical evidence to be presented.',
            ],
            [
                'case_id' => 5,
                'date' => now()->subMonths(1),
                'time' => '15:30:00',
                'location' => 'Room 401 - Civil Court',
                'type' => 'Settlement Conference',
                'outcome' => 'Settlement approved',
                'notes' => 'Parties agreed to settlement terms.',
            ],
            [
                'case_id' => 6,
                'date' => now()->addDays(45),
                'time' => '10:00:00',
                'location' => 'Room 204 - Superior Court',
                'type' => 'Trial',
                'outcome' => null,
                'notes' => 'Full trial proceedings scheduled.',
            ],
        ];

        foreach ($hearings as $hearing) {
            Hearing::create($hearing);
        }
    }
}
