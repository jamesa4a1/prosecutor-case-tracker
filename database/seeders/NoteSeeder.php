<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        $notes = [
            // Case 1 Notes
            [
                'case_id' => 1,
                'user_id' => 1,
                'content' => 'Initial interview with suspect conducted. Suspect admitted to taking vehicle but claims it was for joyride, not theft.',
            ],
            [
                'case_id' => 1,
                'user_id' => 1,
                'content' => 'Vehicle recovered at abandoned warehouse. Forensic examination scheduled for Monday.',
            ],
            [
                'case_id' => 1,
                'user_id' => 1,
                'content' => 'Surveillance footage obtained from nearby businesses. Confirms suspect\'s whereabouts on date of incident.',
            ],
            // Case 2 Notes
            [
                'case_id' => 2,
                'user_id' => 1,
                'content' => 'Bank records subpoenaed. Wire transfers traced to offshore account in Cayman Islands.',
            ],
            [
                'case_id' => 2,
                'user_id' => 1,
                'content' => 'Digital forensics reveals email communications discussing victim targeting strategies.',
            ],
            // Case 3 Notes
            [
                'case_id' => 3,
                'user_id' => 1,
                'content' => 'Victim hospitalized with serious injuries. Medical records to be obtained.',
            ],
            [
                'case_id' => 3,
                'user_id' => 1,
                'content' => '5 witnesses present at scene. Statements collected and pending analysis.',
            ],
            // Case 4 Notes
            [
                'case_id' => 4,
                'user_id' => 1,
                'content' => 'System administrator notified. Unauthorized access logs being preserved for evidence.',
            ],
            [
                'case_id' => 4,
                'user_id' => 1,
                'content' => 'IP address traced to residential address. Warrant application prepared.',
            ],
            // Case 5 Notes
            [
                'case_id' => 5,
                'user_id' => 1,
                'content' => 'Settlement agreement reached: XYZ Ltd to pay $150,000 to ABC Corp.',
            ],
            [
                'case_id' => 5,
                'user_id' => 1,
                'content' => 'Case successfully resolved. All parties satisfied with outcome.',
            ],
            // Case 6 Notes
            [
                'case_id' => 6,
                'user_id' => 1,
                'content' => 'Drug sample sent to lab for analysis. Preliminary field test indicates methamphetamine.',
            ],
            [
                'case_id' => 6,
                'user_id' => 1,
                'content' => 'Defendant has prior conviction for similar offense in 2022.',
            ],
        ];

        foreach ($notes as $note) {
            Note::create($note);
        }
    }
}
