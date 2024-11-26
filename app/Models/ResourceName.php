<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceName extends Model
{
    use HasFactory;

	protected $table = "resource_names";

	protected $fillable = [
		'name',
		'category',
		'description',
	];
}