<?php

namespace App\Models\Traits;


use Illuminate\Database\Eloquent\Builder;

trait Sortable {
    public function scopeSort(Builder $builder, array $dataValidated) {
        $sortFields = $this->getSortFields($dataValidated);

        foreach($sortFields as $field => $direction) {
            $builder->orderBy($field, $direction);
        }

        return $builder;
    }

    protected function getSortFields(array $dataValidated) {
        $sortFields = [];

        if (isset($dataValidated['sort_field'])) {
            $sortField = $dataValidated['sort_field'];

            list($field, $direction) = explode(",", $sortField);

            if (in_array($direction, ['asc', 'desc'])) {
                $sortFields[$field] = $direction;
            }
        }

        if(empty($sortFields)) {
            $sortFields['created_at'] = 'asc';
        }

        return $sortFields;
    }
}
