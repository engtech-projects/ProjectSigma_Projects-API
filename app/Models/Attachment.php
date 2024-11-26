<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    use HasFactory;

	protected $table = "attachments";

	protected $fillable = [
		'project_id',
		'name',
		'path',
		'mime_type',
	];

	public function project() : BelongsTo
	{
		return $this->belongsTo(Project::class);
	}

}
