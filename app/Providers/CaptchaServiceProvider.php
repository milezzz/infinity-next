<?php namespace App\Providers;

use App\Support\Captcha;
use App\Validators\CaptchaValidator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;

class CaptchaServiceProvider extends ServiceProvider {
	
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	
	/**
	 *
	 * Boot the service provider.
	 *
	 * @return null
	 */
	public function boot()
	{
		//$this->mergeConfigFrom('captcha');
		
		/**
		 * @param $app
		 * @return Captcha
		 */
		$this->app->bind('captcha', function($app)
		{
			return new Captcha(
				$app['Illuminate\Filesystem\Filesystem'],
				$app['Illuminate\Config\Repository'],
				$app['Intervention\Image\ImageManager'],
				$app['Illuminate\Session\Store'],
				$app['Illuminate\Hashing\BcryptHasher'],
				$app['Illuminate\Support\Str']
			);
		});
		
		/**
		 * @param Captcha $captcha
		 * @return \Intervention\Image\ImageManager
		 */
		$this->app['router']->get('cp/captcha', function(Captcha $captcha)
		{
			return $captcha->create();
		});
		
		/**
		 * @param Captcha $captcha
		 * @param $config
		 * @return \Intervention\Image\ImageManager
		 */
		$this->app['router']->get('cp/captcha/{config}', function(Captcha $captcha, $config)
		{
			return $captcha->create($config);
		});
		
		$this->registerValidationRules($this->app['validator']);
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		
	}
	
	protected function registerValidationRules($validator)
	{
		$validator->extend("captcha", 'App\Validators\CaptchaValidator@validateCaptcha', 'validation.captcha');
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['captcha'];
	}
}
