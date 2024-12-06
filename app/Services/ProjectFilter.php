<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Enums\ProjectStatus;
use App\Enums\ProjectStage;

class ProjectFilter
{
    public static function apply(Builder $query, Request $request)
    {
        if ($request->has('stage')) {

            if( $request->stage == ProjectStage::AWARDED->label() ) {
                $query->internal();
            }
        }

        if ($request->has('status')) {

            if( $request->status == ProjectStatus::ONGOING->label() ) {
                $query->active();
            }

            if( $request->status == ProjectStatus::ARCHIVED->label() ) {
                $query->archived();
            }
        }

        if ($request->has('sort')) {

            if( $request->sort == 'asc' ) {
                $query->oldest();
            }

            if( $request->sort == 'desc' ) {
                $query->latest();
            }
        }

        if ($request->has('paginate')) {

            $perPage = (int)$request->per_page ?? 10;

            if( $request->paginate == true ) {
                return $query->paginate($perPage);
            } 

        }

        return $query->get();
    }
}
