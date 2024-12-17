<?php

namespace App\Http\Controllers\Api\V1\Command;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Http;

class SyncEmployees extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $apiUrl = config('services.url.hrms_api_url');
        $apiKey = config('services.sigma.secret_key');
        $token = $request->bearerToken();

        try {

            $response = Http::withToken($token)
                ->acceptJson()
                ->get($apiUrl.'/api/employee/list');

            if( $response->ok() ) {
               
                $employees = $response->json()['data'] ?? [];
                
                DB::transaction(function () use ($employees) {

                    foreach ($employees as $employee) {
                        Employee::updateOrCreate(
                            ['employee_id' => $employee['id']], // Prevent duplicate employees
                            [
                                'first_name' => $employee['first_name'],
                                'middle_name' => $employee['middle_name'],
                                'family_name' => $employee['family_name'],
                                'deleted_at' => $employee['deleted_at'] ? Carbon::parse($employee['deleted_at'])->format('Y-m-d H:i:s') : null,
                                'created_at' => $employee['created_at'] ? Carbon::parse($employee['created_at'])->format('Y-m-d H:i:s') : null,
                                'updated_at' => $employee['updated_at'] ? Carbon::parse($employee['updated_at'])->format('Y-m-d H:i:s') : null,
                            ]
                        );
                    }
                });
            }
		
		} catch (\Throwable $e) {
			return ['error' => $e->getMessage()];
		}

    }
}
