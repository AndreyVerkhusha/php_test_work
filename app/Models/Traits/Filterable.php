<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable {
    public function scopeFilter(Builder $builder, $filter) {
        $filter->apply($builder);

        return $builder;
    }
}
