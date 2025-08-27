<?php

namespace App\Http\Controllers;

use App\Models\DailySchedule;
use Illuminate\Http\Request;

class DailyScheduleController extends Controller
{
    protected $dailySchedule;
    public function __construct()
    {
        $this->dailySchedule = DailySchedule::class;
    }

    public function delete($dailyScheduleId)
    {
        $dailySchedule = $this->dailySchedule::findOrFail($dailyScheduleId);
        $dailySchedule->delete();
        return response()->json([
            'success' => true,
            'message' => 'Daily schedule deleted successfully',
            'daily_schedule' => $dailySchedule,
        ], 200);
    }

    public function restore($dailyScheduleId)
    {
        $dailySchedule = $this->dailySchedule::withTrashed()->findOrFail($dailyScheduleId);
        $dailySchedule->restore();
        return response()->json([
            'success' => true,
            'message' => 'Daily schedule restored successfully',
            'daily_schedule' => $dailySchedule,
        ], 200);
    }
}
