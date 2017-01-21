<?php
namespace TorNas\Providers;

use Illuminate\Support\ServiceProvider;

use Transmission\Client;
use Transmission\Transmission;

class TransmissionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Transmission::class, function ($app) {
            $config = $app['config']['transmission'];

            $transmission = new Transmission();
            $client       = new Client($config['host'], $config['port'], $config['path']);

            if ($config['authenticate']) {
                $client->authenticate($config['username'], $config['password']);
            }

            $transmission->setClient($client);

            return $transmission;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
