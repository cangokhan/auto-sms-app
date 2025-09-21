<?php

namespace App\Providers;

use App\Repositories\MessageRepositoryInterface;
use App\Repositories\EloquentMessageRepository;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MessageRepositoryInterface::class, EloquentMessageRepository::class);
    }
        
    public function boot(): void
    {
        //
    }
}
