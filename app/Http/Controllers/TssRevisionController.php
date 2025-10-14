<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SummarizedTssRevisionResource;
use App\Models\Project;
use App\Models\Revision;
use App\Models\TssRevision;
use App\Services\TssRevisionService;
use Illuminate\Http\Request;

class TssRevisionController extends Controller
{
    protected $service;

    public function __construct(TssRevisionService $service)
    {
        $this->service = $service;
    }
    public function store(Project $project, Request $request)
    {
        $this->service->createTssRevision($project, $request->user(), $request->notes);
        return response()->json([
            'success' => true,
            'message' => 'TSS revision created successfully',
        ], 201);
    }
}
