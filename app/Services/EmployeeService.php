<?php

namespace App\Services;

use App\Models\Employee;
use DB;

class EmployeeService
{
    protected $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function withPagination($request = [])
    {
        $query = $this->employee->query();
        $query = $query->when(isset($request['key']), function ($query) use ($request) {
            return $query->where('name', 'LIKE', "%{$request['key']}%");
        });

        return $query->paginate(config('services.pagination.limit'));
    }

    public function all($request = [])
    {
        return $this->employee->all($request);
    }

    public function create(array $attr)
    {
        return DB::transaction(function () use ($attr) {
            $data = Employee::create($attr);

            return $data;
        });
    }

    public function update(Employee $employee, array $attr)
    {
        return DB::transaction(function () use ($employee, $attr) {
            $employee->fill($attr)->save();

            return $employee;
        });
    }

    public function delete(Employee $employee)
    {
        return DB::transaction(function () use ($employee) {
            $employee->delete();

            return $employee;
        });
    }
}
