<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use DB;
use Event;
use App\Models\SiteSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      include __DIR__ . '/../Http/Macros.php';

      // current password validation rule
      Validator::extend('current_password', function ($attribute, $value, $parameters, $validator) {
        return Hash::check($value, Auth::user()->password);
      });

	    if (env('APP_ENV') === 'local') {
	        DB::connection()->enableQueryLog();        
	    }
		  view()->share('site_settings', SiteSetting::first());
    }
}
