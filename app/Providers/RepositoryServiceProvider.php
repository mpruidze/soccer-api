<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Repositories\PlayersRepositoryContract;
use App\Contracts\Repositories\TeamsRepositoryContract;
use App\Contracts\Repositories\TransfersRepositoryContract;
use App\Contracts\Repositories\UsersRepositoryContract;
use App\Repositories\PlayersRepository;
use App\Repositories\TeamsRepository;
use App\Repositories\TransfersRepository;
use App\Repositories\UsersRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    private const array REPOSITORIES = [
        UsersRepositoryContract::class => UsersRepository::class,
        TeamsRepositoryContract::class => TeamsRepository::class,
        PlayersRepositoryContract::class => PlayersRepository::class,
        TransfersRepositoryContract::class => TransfersRepository::class,
    ];

    public function register(): void
    {
        foreach (self::REPOSITORIES as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    public function boot(): void {}
}
