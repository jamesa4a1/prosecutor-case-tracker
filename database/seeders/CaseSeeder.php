<?php

namespace Database\Seeders;

use App\Models\CaseModel;
use Illuminate\Database\Seeder;

class CaseSeeder extends Seeder
{
    public function run(): void
    {
        $cases = [
            [
                'case_number' => 'CASE-2025-001',
                'title' => 'State v. John Doe - Theft Case',
                'type' => 'Criminal',
                'offense' => 'Grand Theft Auto',
                'date_filed' => now()->subMonths(3),
                'status' => 'Under Investigation',
                'prosecutor_id' => 1,
                'investigating_officer_id' => 2,
                'agency_station' => 'Central Police Station',
                'notes' => 'High-value vehicle theft. Suspect apprehended at scene.',
            ],
            [
                'case_number' => 'CASE-2025-002',
                'title' => 'State v. Jane Smith - Fraud Case',
                'type' => 'Criminal',
                'offense' => 'Wire Fraud',
                'date_filed' => now()->subMonths(2),
                'status' => 'Filed in Court',
                'prosecutor_id' => 1,
                'investigating_officer_id' => 3,
                'agency_station' => 'Financial Crimes Unit',
                'notes' => 'Online scam targeting elderly residents. Evidence collected.',
            ],
            [
                'case_number' => 'CASE-2025-003',
                'title' => 'State v. Robert Johnson - Assault',
                'type' => 'Criminal',
                'offense' => 'Aggravated Assault',
                'date_filed' => now()->subMonths(1),
                'status' => 'For Filing',
                'prosecutor_id' => 2,
                'investigating_officer_id' => 4,
                'agency_station' => 'North Precinct',
                'notes' => 'Incident occurred at local bar. Multiple witnesses available.',
            ],
            [
                'case_number' => 'CASE-2025-004',
                'title' => 'State v. Michael Chen - Cybercrime',
                'type' => 'Special',
                'offense' => 'Unauthorized Computer Access',
                'date_filed' => now()->subDays(15),
                'status' => 'Under Investigation',
                'prosecutor_id' => 2,
                'investigating_officer_id' => 3,
                'agency_station' => 'Cyber Crime Division',
                'notes' => 'Hacking incident affecting local government systems.',
            ],
            [
                'case_number' => 'CASE-2025-005',
                'title' => 'ABC Corp v. XYZ Ltd - Contract Dispute',
                'type' => 'Civil',
                'offense' => 'Breach of Contract',
                'date_filed' => now()->subMonths(4),
                'status' => 'Closed',
                'prosecutor_id' => 3,
                'investigating_officer_id' => null,
                'agency_station' => 'Commercial Courts',
                'notes' => 'Settlement reached between parties. Case resolved.',
            ],
            [
                'case_number' => 'CASE-2025-006',
                'title' => 'State v. Sarah Williams - Drug Possession',
                'type' => 'Criminal',
                'offense' => 'Possession with Intent to Distribute',
                'date_filed' => now()->subMonths(2),
                'status' => 'Filed in Court',
                'prosecutor_id' => 3,
                'investigating_officer_id' => 1,
                'agency_station' => 'Narcotics Division',
                'notes' => 'Controlled substance found during traffic stop.',
            ],
        ];

        foreach ($cases as $case) {
            CaseModel::create($case);
        }
    }
}
