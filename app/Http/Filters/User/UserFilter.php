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
            self::PHONE => [$this, 'getPhone'],
            self::LAST_NAME => [$this, 'getLastName'],
            self::FIRST_NAME => [$this, 'getFirstName'],
            self::MIDDLE_NAME => [$this, 'getMiddleName'],
            self::CREATED_AT => [$this, 'getCreatedAt']
        ];
    }

    public function getPhone(Builder $builder, $value) {
        $builder->where(self::PHONE, 'LIKE', "%$value%");
    }

    public function getLastName(Builder $builder, $value) {
        $builder->where(self::LAST_NAME, 'LIKE', "%$value%");
    }

    public function getFirstName(Builder $builder, $value) {
        $builder->where(self::FIRST_NAME, 'LIKE', "%$value%");
    }

    public function getMiddleName(Builder $builder, $value) {
        $builder->where(self::MIDDLE_NAME, 'LIKE', "%$value%");
    }

    public function getCreatedAt(Builder $builder, $value) {
        if (str_contains($value, ' ')) {
            $builder->where(self::CREATED_AT, '=', $value);
        } else {
            $builder->where(self::CREATED_AT, '>=', $value . ' 00:00:00')
                ->where(self::CREATED_AT, '<=', $value . ' 23:59:59');
        }
    }
}
