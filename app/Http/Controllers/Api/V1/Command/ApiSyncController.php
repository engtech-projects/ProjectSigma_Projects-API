<?php

namespace App\Http\Controllers\Api\V1\Command;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Collection;

class ApiSyncController extends Controller
{
    public function sync(Request $request)
    {
        $token = $request->bearerToken();

        try {

            $response = Http::withToken($token)
                ->acceptJson()
                ->get(config('services.url.hrms_api_url').'/api/employee/users-list');

            if( $response->ok() ) {

                $users = $response->json()['data'] ?? [];

                DB::transaction(function () use ($users) {

                    foreach ($users as $user) {
                        User::updateOrCreate(
                            ['user_id' => $user['id']], // Prevent duplicate users
                            [
                                'employee_id' => $user['employee_id'] ?? null,
                                'name' => $user['name'],
                                'email' => $user['email'],
                                'password' => Hash::make('password'),
                                'is_admin' => ($user['type'] == 'Administrator') ? true : false,
                                'deleted_at' => $user['deleted_at'] ? Carbon::parse($user['deleted_at'])->format('Y-m-d H:i:s') : null,
                                'created_at' => $user['created_at'] ? Carbon::parse($user['created_at'])->format('Y-m-d H:i:s') : null,
                                'updated_at' => $user['updated_at'] ? Carbon::parse($user['updated_at'])->format('Y-m-d H:i:s') : null,
                            ]
                        );
        
                    }

                });
            }
		
			
		} catch (\Throwable $e) {
			// Log the exception for debugging purposes
			// Log::error('Project Update Error: ' . $e->getMessage(), ['exception' => $e]);
			// Return response
			return ['error' => $e->getMessage()];
		}

    }
}
