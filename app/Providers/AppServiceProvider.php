<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use  Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\History;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $carbon = new Carbon;
        $His = new History;
        $branch = new User;
        $nobs = ["01","02"];
        $data =['carbon'=>$carbon, 'His'=>$His, 'branch'=>$branch,'nobs'=>$nobs];
        view()->share ('data', $data); 
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
