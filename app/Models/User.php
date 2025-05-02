<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @mixin \Eloquent
 */

class User extends Authenticatable implements JWTSubject {
    use HasFactory, SoftDeletes, Notifiable, Filterable;

    public $guarded = [];

    protected $keyType = 'string';
    public $incrementing = false;
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    public function getJWTCustomClaims() {
        return [];
    }
}
