<?php

namespace App\Http\Filters\User;

use App\Http\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class UserFilter extends AbstractFilter {
    public const PHONE = 'phone';
    public const LAST_NAME = 'last_name';
    public const FIRST_NAME = 'first_name';
    public const MIDDLE_NAME = 'middle_name';
    public const CREATED_AT = 'created_at';

    protected function getCallbacks(): array {
        return [
            self::PHONE => [$this, 'phone'],
        ];
    }

    public function phone(Builder $builder, $value) {
        $builder->where(self::PHONE, $value);
    }
}
