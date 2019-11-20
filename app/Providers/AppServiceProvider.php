<?php

namespace App\Providers;

use App\Services\Sms\Smsc;
use App\Services\Sms\SmsSender;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->singleton(SmsSender::class, function($app) {
            $config = $app->make('config')->get('sms');

            if (!empty($config['url'])) {
                return new Smsc($config['app_id'], $config['url']);
            }
            return new Smsc($config['app_id']);
        });
    }
}
