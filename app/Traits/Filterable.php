<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait Filterable
{
    /**
     * Apply filters to the query based on the provided request.
     *
     * @param Builder $query
     * @param Request|array $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, $filters)
    {
        if ($filters instanceof Request) {
            $filters = $filters->all();
        }

        foreach ($filters as $key => $value) {

            $scopeMethod = Str::title(Str::lower($key));
            $scope = 'scope' . $scopeMethod;
            $withParams = false;
    
            if (Str::lower($key) === 'status' && is_string($value)) {
                
                $scopeMethod = Str::title(Str::lower($value));
                $scope = 'scope' . $scopeMethod;

            } else {
                $withParams = true;
            }

            if (method_exists($this, $scope)) {

                if( $withParams )
                {
                    $query->{$scopeMethod}($value);
                }else {
                    $query->{$scopeMethod}();
                }
                
            }



        }

        return $query;
    }

    public function scopeRetrieve(Builder $query, $paginate = false, $perPage = 10)
    {
        return ($paginate) ? $query->paginate($perPage) : $query->get();
    }
}