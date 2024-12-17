<?php

namespace App\Http\Controllers\Api\V1\Command;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Http;

class SyncUsers extends Controller
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
			return ['error' => $e->getMessage()];
		}
    }
}
