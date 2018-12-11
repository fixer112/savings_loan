<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        $nobs = [];
        for ($i = 1; $i <=9 ; $i++) {
            $value = $i < 10 ? "0".$i : $i;
            array_push($nobs, $value);
        }
        //$nobs = ["01","02","03"];
        $data =['carbon'=>$carbon, 'His'=>$His, 'branch'=>$branch,'nobs'=>$nobs];
        view()->share ('data', $data); 

        Blade::directive('money', function($amount){
            return "<?php echo '₦'. number_format($amount,2);?>";
        });
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
