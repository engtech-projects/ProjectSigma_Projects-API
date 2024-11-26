<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Phase extends Model
{
    use HasFactory;

	protected $table = "phases";

	protected $fillable = [
		'project_id',
		'name',
		'description',
		'total_cost',
	];

	public function project() : BelongsTo
	{
		return $this->belongsTo(Project::class);
	}

	public function tasks() : HasMany
	{
		return $this->hasMany(Task::class);
	}

}
