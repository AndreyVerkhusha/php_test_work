<?php

namespace App\Listeners\User;

use App\Events\User\UserDeleted;
use App\Models\History;
use Str;

class LogUserDeleted
{
    /**
     * Handle the event.
     */
    public function handle(UserDeleted $event): void
    {
        History::create([
            'id' => Str::uuid(),
            'model_id' =>$event->user->id,
            'model_name' => 'User deleted event',
            'before' => json_encode($event->user),
            'after' => null,
            'action' => 'deleted'
        ]);
    }
}
