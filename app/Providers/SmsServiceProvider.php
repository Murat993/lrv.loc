<?php

namespace App\Providers;

use App\Entity\User\User;
use App\Services\Sms\ArraySender;
use App\Services\Sms\Smsc;
use App\Services\Sms\SmsSender;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->singleton(SmsSender::class, function($app) {
            $config = $app->make('config')->get('sms');

            switch ($config['driver']) {
                case 'smsc':
                    $param = $config['drivers']['smsc'];
                    if (!empty($param['url'])) {
                        return new Smsc($param['app_id'], $param['url']);
                    }
                    return new Smsc($param['app_id']);
                case 'array':
                    return new ArraySender();
                default:
                    throw new \InvalidArgumentException('Undefined SMS driver ' . $config['driver']);
            }
        });
    }
}
