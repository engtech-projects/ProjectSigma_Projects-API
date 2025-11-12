<?php

namespace App\Http\Services\ApiServices;

use App\Models\SetupAccessibilities;
use App\Models\SetupDepartments;
use App\Models\SetupEmployees;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HrmsSecretKeyService
{
    protected $apiUrl;
    protected $authToken;

    public function __construct()
    {
        $this->apiUrl = config('services.url.hrms_api_url');
        $this->authToken = config('services.sigma.secret_key');
        if (empty($this->authToken)) {
            throw new \InvalidArgumentException('HRMS secret key is not configured');
        }
        if (empty($this->apiUrl)) {
            throw new \InvalidArgumentException('HRMS API URL is not configured');
        }
    }

    public function syncAll()
    {
        $syncEmployees = $this->syncEmployees();
        if (!$syncEmployees) {
            throw new \Exception("Employee sync failed.");
        }
        $syncUsers = $this->syncUsers();
        if (!$syncUsers) {
            throw new \Exception("User sync failed.");
        }
        $syncDepartments = $this->syncDepartments();
        if (!$syncDepartments) {
            throw new \Exception("Department sync failed.");
        }
        $syncAccessibilities = $this->syncAccessibilities();
        if (!$syncAccessibilities) {
            throw new \Exception("Accessibility sync failed.");
        }
        return true;
    }

    public function syncEmployees()
    {
        $employees = $this->getAllEmployees();
        $employees = collect($employees)->map(fn ($e) => collect($e)->only([
            'id',
            'first_name',
            'middle_name',
            'family_name',
            'nick_name',
            'current_position',
            'digital_signature',
            'created_at',
            'updated_at',
            'deleted_at',
        ])->toArray())->toArray();
        try {
            SetupEmployees::upsert(
                $employees,
                ['id'],
                [
                    'first_name',
                    'middle_name',
                    'family_name',
                    'nick_name',
                    'current_position',
                    'digital_signature',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to sync employees', ['error' => $e->getMessage()]);
            throw new \Exception("Employee sync failed: " . $e->getMessage());
        }
        return true;
    }

    public function syncUsers()
    {
        $users = $this->getAllUsers();
        try {
            User::upsert(
                $users,
                ['id'],
                [
                    "name",
                    "email",
                    "email_verified_at",
                    "password",
                    "remember_token",
                    "type",
                    "accessibilities",
                    "employee_id",
                    "created_at",
                    "updated_at",
                    "deleted_at",
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to sync users', ['error' => $e->getMessage()]);
            throw new \Exception("User sync failed: " . $e->getMessage());
        }
        return true;
    }

    public function syncDepartments()
    {
        $departments = $this->getAllDepartments();
        try {
            SetupDepartments::upsert(
                $departments,
                ['id'],
                [
                    'code',
                    'department_name',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to sync departments', ['error' => $e->getMessage()]);
            throw new \Exception("Department sync failed: " . $e->getMessage());
        }
        return true;
    }

    public function syncAccessibilities()
    {
        $accessibilities = $this->getAllAccessibilities();
        try {
            SetupAccessibilities::upsert(
                $accessibilities,
                ['id'],
                [
                    'accessibilities_name',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to sync accessibilities', ['error' => $e->getMessage()]);
            throw new \Exception("Accessibility sync failed: " . $e->getMessage());
        }
        return true;
    }

    public function getAllEmployees()
    {
        $response = Http::withToken($this->authToken)
            ->timeout(30)
            ->withUrlParameters([
                'paginate' => false,
                'sort' => 'asc',
            ])
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/employee');
        if (!$response->successful()) {
            Log::warning('HRMS API request failed', [
                'endpoint' => 'employee',
                'status' => $response->status(),
                'error' => $response->body()
            ]);
            return [];
        }
        return $response->json("data") ?: [];
    }

    public function getAllUsers()
    {
        $response = Http::withToken($this->authToken)
            ->timeout(30)
            ->withUrlParameters([
                "paginate" => false,
                "sort" => "asc"
            ])
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/user');
        if (!$response->successful()) {
            Log::warning('HRMS API request failed', [
                'endpoint' => 'user',
                'status' => $response->status(),
                'error' => $response->body()
            ]);
            return [];
        }
        return $response->json("data") ?: [];
    }

    public function getAllDepartments()
    {
        $response = Http::withToken($this->authToken)
            ->timeout(30)
            ->withUrlParameters([
                "paginate" => false,
                "sort" => "asc"
            ])
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/department');
        if (!$response->successful()) {
            Log::warning('HRMS API request failed', [
                'endpoint' => 'department',
                'status' => $response->status(),
                'error' => $response->body()
            ]);
            return [];
        }
        return $response->json("data") ?: [];
    }

    public function getAllAccessibilities()
    {
        $response = Http::withToken($this->authToken)
            ->timeout(30)
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/accessibilities');
        if (!$response->successful()) {
            Log::warning('HRMS API request failed', [
                'endpoint' => 'accessibilities',
                'status' => $response->status(),
                'error' => $response->body()
            ]);
            return [];
        }
        return $response->json("data") ?: [];
    }
}
