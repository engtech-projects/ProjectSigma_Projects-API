<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

	protected $table = "tasks";

	protected $fillable = [
		'phase_id',
		'name',
		'description',
		'quantity',
		'unit',
		'unit_price',
		'amount',
	];

	public function phase() : BelongsTo
	{
		return $this->belongsTo(Phase::class);
	}

	public function resources() : HasMany
	{
		return $this->hasMany(Resource::class);
	}
}
