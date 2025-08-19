<?php

namespace App\Services;

use App\Exceptions\ScheduleConflictException;
use App\Models\Project;
use Illuminate\Validation\ValidationException;
use App\Models\TaskSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Request;

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
            ->filterByItemId($filter['item_id'] ?? null)
            ->filterByDate($filter['date_from'] ?? null, $filter['date_to'] ?? null)
            ->filterByStatus($filter['status'] ?? null)
            ->sortByField($filter['sort_by'] ?? 'updated_at', $filter['order'] ?? 'desc')
            ->paginate(config('services.pagination.limit'));
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            if ($this->hasDateConflict($data['item_id'], $data['original_start'], $data['original_end'])) {
                throw ValidationException::withMessages([
                    'date_conflict' => 'The provided dates conflict with an existing schedule.',
                ]);
            }
            $exactDurationDays = Carbon::parse($data['original_start'])
                ->diffInDays(Carbon::parse($data['original_end'])) + 1;
            if ($data['duration_days'] != $exactDurationDays) {
                $data['duration_days'] = $exactDurationDays;
            } elseif (empty($data['duration_days'])) {
                $data['duration_days'] = $exactDurationDays;
            }
            $data['current_start'] ??= $data['original_start'];
            $data['current_end'] ??= $data['original_end'];
            return $this->taskSchedule->create($data);
        });
    }

    private function hasDateConflict(int $itemId, string $startDate, string $endDate): bool
    {
        return $this->taskSchedule->where('item_id', $itemId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('original_start', [$startDate, $endDate])
                    ->orWhereBetween('original_end', [$startDate, $endDate]);
            })
            ->exists();
    }

    public function updateTaskSchedule($id, array $data)
    {
        return DB::transaction(function () use ($data, $id) {
            $taskSchedule = $this->taskSchedule->findOrFail($id);
            if (empty($taskSchedule->original_start) && isset($data['current_start'])) {
                $taskSchedule->original_start = $data['current_start'];
            }
            if (empty($taskSchedule->original_end) && isset($data['current_end'])) {
                $taskSchedule->original_end = $data['current_end'];
            }
            $itemId = $data['item_id'] ?? $taskSchedule->item_id;
            $currentStart = Carbon::parse($data['current_start']);
            $currentEnd = Carbon::parse($data['current_end']);
            $conflicts = $this->taskSchedule->where('item_id', $itemId)
                ->where('id', '!=', $id)
                ->where(function ($query) use ($currentStart, $currentEnd) {
                    $query->where('current_start', '<', $currentEnd)
                          ->where('current_end', '>', $currentStart);
                })
                ->get();
            if ($conflicts->isNotEmpty()) {
                throw new ScheduleConflictException(
                    $conflicts,
                    $this->getSuggestedSlots($itemId, $currentStart, $currentEnd)
                );
            }
            $exactDurationDays = $currentStart->diffInDays($currentEnd) + 1;
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

