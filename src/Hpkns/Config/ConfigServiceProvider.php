<?php namespace Hpkns\Config;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->commands([
            'Hpkns\Config\Commands\ConfigImport',
        ]);
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{

	}

}

