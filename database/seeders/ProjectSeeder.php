<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Enums\ProjectStatus;
use App\Enums\ProjectStage;


class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the CSV file
         $file = database_path('seeders/projects.csv');
        // Open the CSV file
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0); // Use the first row as the header

        foreach ($csv as $record) {

            Project::updateOrCreate(
                ['id' => $record['id']], // Prevent duplicate records
                [
                    'uuid' => (string) Str::uuid(),
                    'parent_project_id' => null,
                    'contract_id' => $record['contract_id'],
                    'code' => $record['project_code'],
                    'name' => $record['contract_name'],
                    'location' => $record['contract_location'],
                    'amount' => $record['contract_amount'],
                    'duration' => $record['contract_duration'],
                    'nature_of_work' => $record['nature_of_work'],
                    'contract_date' => $record['date_of_contract'] ? Carbon::createFromFormat('d/m/Y', $record['date_of_contract'])->format('Y-m-d') : null,
                    'ntp_date' => $record['date_of_ntp'] ? Carbon::createFromFormat('d/m/Y', $record['date_of_ntp'])->format('Y-m-d') : null,
                    'noa_date' => $record['date_of_noa'] ? Carbon::createFromFormat('d/m/Y', $record['date_of_noa'])->format('Y-m-d') : null,
                    'license' => $record['license'],
                    'is_original' => false,
                    'version' => 2.0,
                    'status' => ProjectStatus::ONGOING,
                    'stage' => ProjectStage::AWARDED,
                    'deleted_at' => $record['deleted_at'] ? Carbon::createFromFormat('d/m/Y H:i:s', $record['deleted_at'])->format('Y-m-d H:i:s') : null,
                    'created_at' => $record['created_at'] ? Carbon::createFromFormat('d/m/Y H:i:s', $record['created_at'])->format('Y-m-d H:i:s') : null,
                    'updated_at' => $record['updated_at'] ? Carbon::createFromFormat('d/m/Y H:i:s', $record['updated_at'])->format('Y-m-d H:i:s') : null,
                    'project_identifier' => $record['project_identifier'],
                    'implementing_office' => $record['implementing_office'],
                    'current_revision_id' => null,
                ]
            );
        }
    }


}
