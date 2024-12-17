<?php

namespace App\Http\Controllers\Api\V1\Command;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Employee;
use App\Models\ItemProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Collection;

class ApiSyncController extends Controller
{
    protected $token;

    public function sync(Request $request)
    {
        $this->token = $request->bearerToken();
        
        // if( $request->has('users') )
        // {
        //     $this->users();
        // }

        // if( $request->has('employees') )
        // {
            return $this->employees();
        // }

    }

    public function users() {

        try {

            $response = Http::withToken($this->token)
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

    public function employees() {

        try {

            $response = Http::withToken($this->token)
                ->acceptJson()
                ->get(config('services.url.hrms_api_url').'/api/employee/list');

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

    public function departments() {}
    
    public function itemProfiles()
    {
        try {

            $response = Http::withToken($this->token)
                ->acceptJson()
                ->get(config('services.url.hrms_api_url').'/api/employee/list');

            if( $response->ok() ) {

                $items = $response->json()['data'] ?? [];
                
                DB::transaction(function () use ($employees) {

                    // foreach ($items as $item) {
                    //     ItemProfile::updateOrCreate(
                    //         ['item_id' => $item['id']], // Prevent duplicate items
                    //         [
                    //             'name' => $employee['first_name'],
                    //             'unit' => $employee['middle_name'],
                    //             'family_name' => $employee['family_name'],
                    //             'deleted_at' => $employee['deleted_at'] ? Carbon::parse($employee['deleted_at'])->format('Y-m-d H:i:s') : null,
                    //             'created_at' => $employee['created_at'] ? Carbon::parse($employee['created_at'])->format('Y-m-d H:i:s') : null,
                    //             'updated_at' => $employee['updated_at'] ? Carbon::parse($employee['updated_at'])->format('Y-m-d H:i:s') : null,
                    //         ]
                    //     );
                    // }
                });
            }
		
		} catch (\Throwable $e) {
			return ['error' => $e->getMessage()];
		}
    }


}
