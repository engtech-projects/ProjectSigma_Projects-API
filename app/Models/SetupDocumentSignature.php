<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetupDocumentSignature extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'position',
        'license',
        'document_type',
    ];
    public const DOCUMENT_TYPES = [
        'bill_of_quantities',
        'detailed_estimates',
        'cash_flow',
        'summary_of_rates',
        'bid_summary',
    ];
}
