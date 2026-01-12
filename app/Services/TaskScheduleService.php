<?php

namespace App\Services;

use App\Exceptions\ScheduleConflictException;
use App\Models\Project;
use Illuminate\Validation\ValidationException;
use App\Models\TaskSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TaskScheduleService
{
    protected $taskSchedule;

    public function __construct(TaskSchedule $taskSchedule)
    {
        $this->taskSchedule = $taskSchedule;
    }

    public function searchAndFilter(array $filter)
    {
        return Project::with('phases.tasks.schedules')
            ->filterByTitle($filter['title'] ?? null)
            ->filterByTimelineClassification($filter['timeline_classification'] ?? null)
            ->filterByItemId($filter['item_id'] ?? null)
            ->filterByDate($filter['date_from'] ?? null, $filter['date_to'] ?? null)
            ->filterByStatus($filter['status'] ?? null)
            ->sortByField($filter['sort_by'] ?? 'updated_at', $filter['order'] ?? 'desc')
            ->paginate(config('services.pagination.limit'));
    }

    public function createTaskSchedule(array $data)
    {
        return DB::transaction(function () use ($data) {
            if ($this->hasDateConflict($data['item_id'], $data['start_date'], $data['end_date'])) {
                throw ValidationException::withMessages([
                    'date_conflict' => 'The provided dates conflict with an existing schedule.',
                ]);
            }
            $exactDurationDays = Carbon::parse($data['start_date'])
                ->diffInDays(Carbon::parse($data['end_date'])) + 1;
            if ($data['duration_days'] != $exactDurationDays) {
                $data['duration_days'] = $exactDurationDays;
            } elseif (empty($data['duration_days'])) {
                $data['duration_days'] = $exactDurationDays;
            }
            return $this->taskSchedule->create($data);
        });
    }

    private function hasDateConflict(int $itemId, string $startDate, string $endDate): bool
    {
        return $this->taskSchedule->where('item_id', $itemId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })
            ->exists();
    }

    public function updateTaskSchedule($id, array $data)
    {
        return DB::transaction(function () use ($data, $id) {
            $taskSchedule = $this->taskSchedule->findOrFail($id);
            $itemId = $data['item_id'] ?? $taskSchedule->item_id;
            $startDate = Carbon::parse($data['start_date']);
            $endDate = Carbon::parse($data['end_date']);
            $conflicts = $this->taskSchedule->where('item_id', $itemId)
                ->where('id', '!=', $id)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<', $endDate)
                        ->where('end_date', '>', $startDate);
                })
                ->get();
            if ($conflicts->isNotEmpty()) {
                throw new ScheduleConflictException(
                    $conflicts,
                    $this->getSuggestedSlots($itemId, $startDate, $endDate)
                );
            }
            $exactDurationDays = $startDate->diffInDays($endDate) + 1;
            $data['duration_days'] = $exactDurationDays;
            $taskSchedule->fill($data);
            $taskSchedule->save();
            return $taskSchedule;
        });
    }

    private function getSuggestedSlots(int $itemId, Carbon $desiredStart, Carbon $desiredEnd)
    {
        $schedules = TaskSchedule::where('item_id', $itemId)
            ->orderBy('current_start', 'asc')
            ->get();
        $slots = [];
        $prevEnd = null;
        $desiredDuration = $desiredStart->diffInDays($desiredEnd) + 1;
        foreach ($schedules as $schedule) {
            $scheduleStart = Carbon::parse($schedule->current_start);
            $scheduleEnd = Carbon::parse($schedule->current_end);
            if ($prevEnd && $prevEnd < $scheduleStart) {
                $gap  = $prevEnd->diffInDays($scheduleStart);
                if ($gap >= $desiredDuration) {
                    $slots[] = [
                        'start' => $prevEnd->copy()->toDateString(),
                        'end' => $schedule->current_start->copy()->subDays()->toDateString(),
                    ];
                }
            }
            $prevEnd = $scheduleEnd->copy()->addDay();
        }
        if ($prevEnd) {
            $slots[] = [
                'start' => $prevEnd->toDateString(),
                'end' => $prevEnd->copy()->addDays($desiredDuration - 1)->toDateString(),
            ];
        }
        return $slots;
    }
}
