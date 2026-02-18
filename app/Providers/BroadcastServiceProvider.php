<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::channel('taskmanager.{id}', function ($user, $id) {
            return (int) $user->id === (int) $id; // only the owner can listen
        });

        require base_path('routes/channels.php');
    }
}
