<?php

namespace App\Services;

use App\Models\Cashflow;
use App\Models\Employee;
use DB;

class CashflowService
{
    protected $cashflow;

    public function __construct(Cashflow $cashflow)
    {
        $this->cashflow = $cashflow;
    }

}
