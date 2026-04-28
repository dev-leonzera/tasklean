<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('time.{id}', function ($user, $id) {
    return $user->times()->where('times.id', $id)->exists() || $user->ownedTimes()->where('id', $id)->exists();
});
