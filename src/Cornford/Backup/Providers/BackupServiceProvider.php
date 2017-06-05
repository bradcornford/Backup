<?php namespace Cornford\Backup\Providers;

use Cornford\Backup\BackupFactory;
use Cornford\Backup\Commands\BackupCommandExport;
use Cornford\Backup\Commands\BackupCommandRestore;
use Cornford\Backup\Exceptions\BackupRestoreException;
use Illuminate\Support\ServiceProvider;

class BackupServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$configPath = __DIR__ . '/../../../config/config.php';
		$this->publishes([$configPath => config_path('backup.php')], 'backup');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$configPath = __DIR__ . '/../../../config/config.php';
		$this->mergeConfigFrom($configPath, 'backup');

        $this->app->singleton(
            'backup',
            function($app)
			{
				$config = array_merge($app['config']->get('database'), $app['config']->get('backup'));

				return (new BackupFactory)->build($config);
			}
        );

        $this->app->singleton(
            'db.export',
            function($app)
			{
				return new BackupCommandExport(new BackupFactory, $app['config']);
			}
        );

        $this->app->singleton(
            'db.restore',
            function($app)
			{
				return new BackupCommandRestore(new BackupFactory, $app['config']);
			}
        );

		$this->commands(
			'db.export',
			'db.restore'
		);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return string[]
	 */
	public function provides()
	{
		return ['backup'];
	}

}
