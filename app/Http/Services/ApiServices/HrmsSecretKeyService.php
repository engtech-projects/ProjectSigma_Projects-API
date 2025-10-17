<?php

namespace App\Http\Services\ApiServices;

use App\Models\Employee;
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
        $syncUsers = $this->syncUsers();
        $syncDepartments = $this->syncDepartments();
        $syncAccessibilities = $this->syncAccessibilities();
        return $syncEmployees && $syncUsers && $syncDepartments && $syncAccessibilities;
    }

    public function syncEmployees()
    {
        $employees = $this->getAllEmployees();
        $employees = collect($employees)->map(fn($e) => collect($e)->only([
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
        return true;
    }

    public function syncUsers()
    {
        $users = $this->getAllUsers();
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
        return true;
    }

    public function syncDepartments()
    {
        $departments = $this->getAllDepartments();
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
        return true;
    }

    public function syncAccessibilities()
    {
        $accessibilities = $this->getAllAccessibilities();
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
        return true;
    }

    public function getAllEmployees()
    {
        $response = Http::withToken($this->authToken)
            ->withUrlParameters([
                'paginate' => false,
                'sort' => 'asc',
            ])
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/employee');
        if (!$response->successful()) {
            return [];
        }
        return $response->json("data") ?: [];
    }

    public function getAllUsers()
    {
        $response = Http::withToken($this->authToken)
            ->withUrlParameters([
                "paginate" => false,
                "sort" => "asc"
            ])
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/user');
        if (!$response->successful()) {
            return [];
        }
        return $response->json("data") ?: [];
    }

    public function getAllDepartments()
    {
        $response = Http::withToken($this->authToken)
            ->withUrlParameters([
                "paginate" => false,
                "sort" => "asc"
            ])
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/department');
        if (!$response->successful()) {
            return [];
        }
        return $response->json("data") ?: [];
    }

    public function getAllAccessibilities()
    {
        $response = Http::withToken($this->authToken)
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/accessibilities');
        if (!$response->successful()) {
            return [];
        }
        return $response->json("data") ?: [];
    }
}
