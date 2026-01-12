<?php

namespace App\Http\Controllers\Api\V1\Accessibility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Role::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'api',
        ]);

        return response()->json($role, 200);
    }
}
