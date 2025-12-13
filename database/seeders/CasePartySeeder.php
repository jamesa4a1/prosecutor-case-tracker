<?php

namespace Database\Seeders;

use App\Models\CaseParty;
use Illuminate\Database\Seeder;

class CasePartySeeder extends Seeder
{
    public function run(): void
    {
        $parties = [
            // Case 1 Parties
            [
                'case_id' => 1,
                'party_type' => 'Defendant',
                'name' => 'John Doe',
                'contact_info' => '(555) 123-4567',
                'role' => 'Primary Defendant',
            ],
            [
                'case_id' => 1,
                'party_type' => 'Victim',
                'name' => 'Maria Garcia',
                'contact_info' => '(555) 987-6543',
                'role' => 'Vehicle Owner',
            ],
            [
                'case_id' => 1,
                'party_type' => 'Witness',
                'name' => 'Officer James Wilson',
                'contact_info' => '(555) 246-8135',
                'role' => 'Arresting Officer',
            ],
            // Case 2 Parties
            [
                'case_id' => 2,
                'party_type' => 'Defendant',
                'name' => 'Jane Smith',
                'contact_info' => '(555) 234-5678',
                'role' => 'Primary Defendant',
            ],
            [
                'case_id' => 2,
                'party_type' => 'Victim',
                'name' => 'Robert Brown',
                'contact_info' => '(555) 876-5432',
                'role' => 'Defrauded Party',
            ],
            [
                'case_id' => 2,
                'party_type' => 'Witness',
                'name' => 'Patricia Lee',
                'contact_info' => '(555) 369-2580',
                'role' => 'Co-victim/Witness',
            ],
            // Case 3 Parties
            [
                'case_id' => 3,
                'party_type' => 'Defendant',
                'name' => 'Robert Johnson',
                'contact_info' => '(555) 345-6789',
                'role' => 'Primary Defendant',
            ],
            [
                'case_id' => 3,
                'party_type' => 'Victim',
                'name' => 'Edward Martinez',
                'contact_info' => '(555) 654-3210',
                'role' => 'Assault Victim',
            ],
            // Case 4 Parties
            [
                'case_id' => 4,
                'party_type' => 'Defendant',
                'name' => 'Michael Chen',
                'contact_info' => '(555) 456-7890',
                'role' => 'Primary Defendant',
            ],
            // Case 5 Parties (Civil)
            [
                'case_id' => 5,
                'party_type' => 'Plaintiff',
                'name' => 'ABC Corporation',
                'contact_info' => '(555) 567-8901',
                'role' => 'Claimant',
            ],
            [
                'case_id' => 5,
                'party_type' => 'Defendant',
                'name' => 'XYZ Limited',
                'contact_info' => '(555) 678-9012',
                'role' => 'Respondent',
            ],
            // Case 6 Parties
            [
                'case_id' => 6,
                'party_type' => 'Defendant',
                'name' => 'Sarah Williams',
                'contact_info' => '(555) 789-0123',
                'role' => 'Primary Defendant',
            ],
            [
                'case_id' => 6,
                'party_type' => 'Witness',
                'name' => 'Detective Lisa Anderson',
                'contact_info' => '(555) 890-1234',
                'role' => 'Investigating Officer',
            ],
        ];

        foreach ($parties as $party) {
            CaseParty::create($party);
        }
    }
}
