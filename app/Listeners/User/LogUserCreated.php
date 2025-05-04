<?php

namespace App\Listeners\User;

use App\Events\User\UserCreated;
use App\Models\History;
use Str;

class LogUserCreated
{

    public function handle(UserCreated $event): void
    {
        History::create([
            'id' => Str::uuid(),
            'model_id' =>$event->user->id,
            'model_name' => 'User created event',
            'before' => null,
            'after' => json_encode($event->user),
            'action' => 'created'
        ]);
    }
}
