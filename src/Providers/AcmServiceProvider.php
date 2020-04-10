<?php

namespace Donjan\AcmClient\Providers;

use Illuminate\Support\ServiceProvider;
use Dotenv\Dotenv;
use Illuminate\Filesystem\Filesystem;
use Donjan\AcmClient\Commands\GetConfig;

class AcmServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Filesystem $filesystem)
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GetConfig::class
            ]);
        }
        $file = base_path() . DIRECTORY_SEPARATOR . 'acm.json';
        if ($filesystem->exists($file)) {
            $configArr = json_decode($filesystem->get($file), true);
            config($configArr);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
//        if (!$this->app->configurationIsCached()) {
//            try {
//                (new Dotenv($this->app->environmentPath(), '.env.nacos'))->overload();
//            } catch (InvalidPathException $e) {
//                
//            }
//        }
    }

}
