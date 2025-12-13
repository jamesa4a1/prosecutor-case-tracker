<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Prosecutor;
use App\Models\CaseModel;
use App\Models\Hearing;
use App\Models\Note;
use App\Models\StatusHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@prosecutor.gov',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
        ]);

        // Create Prosecutor Users
        $prosecutorUser1 = User::create([
            'name' => 'Maria Santos',
            'email' => 'maria.santos@prosecutor.gov',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PROSECUTOR,
            'is_active' => true,
        ]);

        $prosecutorUser2 = User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juan.delacruz@prosecutor.gov',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PROSECUTOR,
            'is_active' => true,
        ]);

        // Create Clerk User
        $clerk = User::create([
            'name' => 'Ana Reyes',
            'email' => 'ana.reyes@prosecutor.gov',
            'password' => Hash::make('password'),
            'role' => User::ROLE_CLERK,
            'is_active' => true,
        ]);

        // Create Prosecutors
        $prosecutor1 = Prosecutor::create([
            'name' => 'Atty. Maria Santos',
            'email' => 'maria.santos@prosecutor.gov',
            'position' => 'Senior State Prosecutor',
            'office' => 'City Prosecutor\'s Office',
            'user_id' => $prosecutorUser1->id,
            'is_active' => true,
        ]);

        $prosecutor2 = Prosecutor::create([
            'name' => 'Atty. Juan Dela Cruz',
            'email' => 'juan.delacruz@prosecutor.gov',
            'position' => 'Assistant City Prosecutor',
            'office' => 'City Prosecutor\'s Office',
            'user_id' => $prosecutorUser2->id,
            'is_active' => true,
        ]);

        $prosecutor3 = Prosecutor::create([
            'name' => 'Atty. Pedro Garcia',
            'email' => 'pedro.garcia@prosecutor.gov',
            'position' => 'State Prosecutor',
            'office' => 'Provincial Prosecutor\'s Office',
            'user_id' => null,
            'is_active' => true,
        ]);

        // Create Sample Cases
        $cases = [
            [
                'case_number' => 'CR-2025-001',
                'title' => 'People vs. Rodriguez - Theft',
                'offense' => 'Qualified Theft',
                'type' => 'Criminal',
                'status' => 'Under Investigation',
                'date_filed' => now()->subDays(30),
                'complainant' => 'ABC Corporation',
                'accused' => 'Roberto Rodriguez',
                'investigating_officer' => 'SPO2 Carlos Mendez',
                'agency_station' => 'PNP Station 5',
                'prosecutor_id' => $prosecutor1->id,
                'court_branch' => 'RTC Branch 45',
                'next_hearing_at' => now()->addDays(14),
            ],
            [
                'case_number' => 'CR-2025-002',
                'title' => 'People vs. Fernandez - Estafa',
                'offense' => 'Estafa',
                'type' => 'Criminal',
                'status' => 'Pending',
                'date_filed' => now()->subDays(15),
                'complainant' => 'Jose Martinez',
                'accused' => 'Linda Fernandez',
                'investigating_officer' => 'PO3 Miguel Torres',
                'agency_station' => 'NBI Manila',
                'prosecutor_id' => $prosecutor2->id,
                'court_branch' => 'RTC Branch 12',
                'next_hearing_at' => now()->addDays(7),
            ],
            [
                'case_number' => 'CR-2025-003',
                'title' => 'People vs. Luna - Homicide',
                'offense' => 'Homicide',
                'type' => 'Criminal',
                'status' => 'Filed',
                'date_filed' => now()->subDays(60),
                'complainant' => 'Heirs of Antonio Cruz',
                'accused' => 'Mario Luna',
                'investigating_officer' => 'SINV Ramon Santos',
                'agency_station' => 'PNP CIDG',
                'prosecutor_id' => $prosecutor1->id,
                'court_branch' => 'RTC Branch 78',
                'next_hearing_at' => now()->addDays(21),
            ],
            [
                'case_number' => 'CV-2025-001',
                'title' => 'Reyes vs. Santos - Property Dispute',
                'offense' => 'Unlawful Detainer',
                'type' => 'Civil',
                'status' => 'Pending',
                'date_filed' => now()->subDays(45),
                'complainant' => 'Familia Reyes',
                'accused' => 'Elena Santos',
                'investigating_officer' => null,
                'agency_station' => null,
                'prosecutor_id' => $prosecutor3->id,
                'court_branch' => 'MTC Branch 5',
                'next_hearing_at' => now()->addDays(10),
            ],
            [
                'case_number' => 'CR-2025-004',
                'title' => 'People vs. Multiple Accused - Drug Trafficking',
                'offense' => 'Violation of RA 9165',
                'type' => 'Special',
                'status' => 'Under Investigation',
                'date_filed' => now()->subDays(10),
                'complainant' => 'PDEA Region 4',
                'accused' => 'Ricky Tan et al.',
                'investigating_officer' => 'Agent Mark Villanueva',
                'agency_station' => 'PDEA Regional Office',
                'prosecutor_id' => $prosecutor2->id,
                'court_branch' => null,
                'next_hearing_at' => null,
            ],
            [
                'case_number' => 'CR-2024-089',
                'title' => 'People vs. Navarro - Cybercrime',
                'offense' => 'Cyber Libel',
                'type' => 'Criminal',
                'status' => 'Closed',
                'date_filed' => now()->subMonths(6),
                'complainant' => 'Gov. Ricardo Gomez',
                'accused' => 'Angelo Navarro',
                'investigating_officer' => 'Agent Lisa Wong',
                'agency_station' => 'NBI Cybercrime Division',
                'prosecutor_id' => $prosecutor1->id,
                'court_branch' => 'RTC Branch 23',
                'next_hearing_at' => null,
            ],
        ];

        foreach ($cases as $caseData) {
            CaseModel::create($caseData);
        }

        // Create Hearings
        $hearings = [
            ['case_id' => 1, 'date_time' => now()->addDays(14)->setTime(9, 0), 'court_branch' => 'RTC Branch 45', 'assigned_prosecutor_id' => $prosecutor1->id, 'result_status' => 'Scheduled'],
            ['case_id' => 1, 'date_time' => now()->subDays(7)->setTime(10, 0), 'court_branch' => 'RTC Branch 45', 'assigned_prosecutor_id' => $prosecutor1->id, 'result_status' => 'Completed', 'remarks' => 'Initial hearing completed. Evidence presentation set for next hearing.'],
            ['case_id' => 2, 'date_time' => now()->addDays(7)->setTime(14, 0), 'court_branch' => 'RTC Branch 12', 'assigned_prosecutor_id' => $prosecutor2->id, 'result_status' => 'Scheduled'],
            ['case_id' => 3, 'date_time' => now()->addDays(21)->setTime(9, 30), 'court_branch' => 'RTC Branch 78', 'assigned_prosecutor_id' => $prosecutor1->id, 'result_status' => 'Scheduled'],
            ['case_id' => 3, 'date_time' => now()->subDays(30)->setTime(9, 30), 'court_branch' => 'RTC Branch 78', 'assigned_prosecutor_id' => $prosecutor1->id, 'result_status' => 'Completed', 'remarks' => 'Arraignment completed. Accused pleaded not guilty.'],
            ['case_id' => 4, 'date_time' => now()->addDays(10)->setTime(10, 0), 'court_branch' => 'MTC Branch 5', 'assigned_prosecutor_id' => $prosecutor3->id, 'result_status' => 'Scheduled'],
        ];

        foreach ($hearings as $hearingData) {
            Hearing::create($hearingData);
        }

        // Create Notes
        $notes = [
            ['case_id' => 1, 'user_id' => $prosecutorUser1->id, 'body' => 'Initial review completed. Evidence appears sufficient for prosecution.'],
            ['case_id' => 1, 'user_id' => $clerk->id, 'body' => 'All documents received and filed.'],
            ['case_id' => 2, 'user_id' => $prosecutorUser2->id, 'body' => 'Witness statements taken. Preparing for preliminary investigation.'],
            ['case_id' => 3, 'user_id' => $admin->id, 'body' => 'Case assigned priority status due to media coverage.'],
            ['case_id' => 5, 'user_id' => $prosecutorUser2->id, 'body' => 'Coordinating with PDEA for additional evidence.'],
        ];

        foreach ($notes as $noteData) {
            Note::create($noteData);
        }

        // Create Status History
        $statusHistories = [
            ['case_id' => 1, 'from_status' => 'Pending', 'to_status' => 'Under Investigation', 'changed_by' => $prosecutorUser1->id, 'changed_at' => now()->subDays(25)],
            ['case_id' => 3, 'from_status' => 'Pending', 'to_status' => 'Under Investigation', 'changed_by' => $prosecutorUser1->id, 'changed_at' => now()->subDays(55)],
            ['case_id' => 3, 'from_status' => 'Under Investigation', 'to_status' => 'Filed', 'changed_by' => $prosecutorUser1->id, 'changed_at' => now()->subDays(40)],
            ['case_id' => 6, 'from_status' => 'Pending', 'to_status' => 'Under Investigation', 'changed_by' => $prosecutorUser1->id, 'changed_at' => now()->subMonths(5)],
            ['case_id' => 6, 'from_status' => 'Under Investigation', 'to_status' => 'Filed', 'changed_by' => $prosecutorUser1->id, 'changed_at' => now()->subMonths(4)],
            ['case_id' => 6, 'from_status' => 'Filed', 'to_status' => 'Closed', 'changed_by' => $admin->id, 'changed_at' => now()->subMonths(1)],
        ];

        foreach ($statusHistories as $historyData) {
            StatusHistory::create($historyData);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('  Admin: admin@prosecutor.gov / password');
        $this->command->info('  Prosecutor: maria.santos@prosecutor.gov / password');
        $this->command->info('  Clerk: ana.reyes@prosecutor.gov / password');
    }
}
