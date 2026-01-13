<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TssTaskScheduleCashflowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->resource; // <- access the actual array
        $items = $data['items'] ?? [];
        $rawTotals = [
            'total_materials' => collect($items)->sum(fn ($item) => (float) str_replace(',', '', $item['total_materials'] ?? 0)),
            'total_equipment' => collect($items)->sum(fn ($item) => (float) str_replace(',', '', $item['total_equipment'] ?? 0)),
            'total_labor'     => collect($items)->sum(fn ($item) => (float) str_replace(',', '', $item['total_labor'] ?? 0)),
            'total_fuel'      => collect($items)->sum(fn ($item) => (float) str_replace(',', '', $item['total_fuel'] ?? 0)),
            'total_overhead'  => collect($items)->sum(fn ($item) => (float) str_replace(',', '', $item['total_overhead'] ?? 0)),
        ];
        $totals = array_map(function ($value) {
            $value = (float) $value;
            $formatted = number_format($value, 2, '.', ',');
            return substr($formatted, -3) === '.00' ? number_format((int)$value, 0, '', ',') : $formatted;
        }, $rawTotals);
        return [
            'month' => $data['month'] ?? null,
            'items' => $items,
            'totals' => $totals,
        ];
    }
}
