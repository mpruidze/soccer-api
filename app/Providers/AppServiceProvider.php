<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->environment('production'));

        $this->configureRateLimiting();
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', static function (Request $request) {
            $limit = $request->user()
                ? Limit::perMinute(200)->by($request->user()->id)
                : Limit::perMinute(60)->by($request->ip());

            return $limit->response(function (Request $request, array $headers) {
                return response()->json([
                    'message' => __('auth.throttle', ['seconds' => 60]),
                ], 429, $headers);
            });
        });
    }
}
