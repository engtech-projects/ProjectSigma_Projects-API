<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends Model
{
    use HasFactory;

	protected $table = "resources";

	protected $fillable = [
		'task_id',
		'name_id',
		'description',
		'quantity',
		'unit',
		'unit_cost',
		'resource_count',
		'total_cost',
	];

	public function task() : BelongsTo
	{
		return $this->belongsTo(Task::class);
	}

}
