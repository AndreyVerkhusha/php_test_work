<?php

namespace App\Listeners\User;

use App\Events\User\UserUpdated;
use App\Models\History;
use Str;

class LogUserUpdated {
    public function handle(UserUpdated $event): void {
        History::create([
            'id'         => Str::uuid(),
            'model_id'   => $event->user->id,
            'model_name' => 'User updated event',
            'before'     => json_encode($event->user->getOriginal()),
            'after'      => json_encode($event->user),
            'action'     => 'updated',
        ]);
    }
}
