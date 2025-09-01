<?php

namespace App\Http\Controllers\Api\V1\Accessibility;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Permission::all(), 200);
    }
}
