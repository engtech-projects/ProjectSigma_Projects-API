<?php

namespace App\Services;

use App\Models\Cashflow;

class CashflowService
{
    protected $cashflow;

    public function __construct(Cashflow $cashflow)
    {
        $this->cashflow = $cashflow;
    }
}
