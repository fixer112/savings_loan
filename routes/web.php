<?php

use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	if (Auth::check()) {
		$role = Auth::user()->role;
    	return redirect('/'.$role);
	}else {
		return redirect('login');
	}
});

Route::get('/home', function () {
        return redirect('/');
});
Route::get('/referal', function (Request $request) {
    if (Auth::check() && Auth::user()->role == 'customer') {
        return redirect('/');
    }else {
    $branch = Auth::user()->mentor;
    if ($request->branch) {
        $branch = $request->branch;
    }
    $referals = User::where('referal', $branch)->get();
     return view('referal', compact('referals', 'branch'));
    }
});
/*Route::get('calculator', 'CalenderController@index');
//Route::post('/calculator', 'CalenderController@calculate');*/

//Admin
Route::get('admin', 'AdminController@index');
//Route::get('admin/history', 'AdminController@history');
Route::get('admin/newcustomer', 'AdminController@newcus');
Route::post('/admin/register', 'AdminController@register');
Route::get('admin/transaction', 'AdminController@newtran');
Route::post('admin/verifytrans', 'AdminController@transaction');


Route::get('admin/searchstaff', function () {
    if (Auth::check() && Auth::user()->role == 'admin') {
    return view('admin.searchstaff');
}else {
    
    return redirect('/');
}
});
Route::get('delete/{user}', 'AdminController@delete');
Route::post('role/{user}', 'AdminController@role');
Route::any('admin/search', 'AdminController@search');
Route::post('admin/searchstaff', 'AdminController@searchstaff');
Route::get('admin/changepass', 'AdminController@changepass');
Route::put('admin/changepass', 'AdminController@changepass');
Route::get('admin/customer/{id}', 'AdminController@viewcustomer');
Route::get('admin/customer/collateral/{id}', 'AdminController@viewcollateral');
Route::put('admin/customer/edit/{id}', 'AdminController@editcustomer');
Route::get('admin/customer/edit/{id}', 'AdminController@editcustomer');
Route::get('admin/aprove/{pendingid}', 'AdminController@approve');
Route::get('admin/reject/{pendingid}', 'AdminController@reject');

Route::get('admin/addstaff', 'AdminController@addstaff');
Route::post('admin/createstaff', 'AdminController@createstaff');
Route::put('admin/editstaff', 'AdminController@editstaff');
Route::get('admin/addadmin', 'AdminController@addadmin');
Route::post('admin/createadmin', 'AdminController@createadmin');
Route::get('admin/addadmin2', 'AdminController@addadmin2');
Route::post('admin/createadmin2', 'AdminController@createadmin2');
Route::get('admin/suspend/{id}', 'AdminController@suspend');
Route::get('admin/unsuspend/{id}', 'AdminController@unsuspend');
Route::get('admin/info', 'AdminController@info');
Route::get('admin/fee/{id}', 'AdminController@fee');
Route::get('admin/veri_interest/{loan}', 'AdminController@veri_interest');
//Route::get('admin/sms', 'AdminController@send');



//Staff
Route::get('staff', 'StaffController@index');
//Route::get('staff/history', 'StaffController@history');
Route::get('staff/newcustomer', 'StaffController@newcus');
Route::post('/staff/register', 'StaffController@register');
Route::get('staff/transaction', 'StaffController@newtran');
Route::post('staff/verifytrans', 'StaffController@transaction');
/*Route::get('staff/search', function () {
	if (Auth::user()->role == 'staff') {
    return view('staff.search');
}else {
	$role = Auth::user()->role;
    return redirect('/'.$role);
}
});*/
Route::get('staff/search', 'StaffController@search');
Route::get('staff/changepass', 'StaffController@changepass');
Route::put('staff/changepass', 'StaffController@changepass');
Route::get('staff/customer/{id}', 'StaffController@viewcustomer');
Route::get('staff/customer/collateral/{id}', 'StaffController@viewcollateral');

//Admin2
Route::get('admin2', 'Admin2Controller@index');
//Route::get('admin2/history', 'Admin2Controller@history');
Route::get('admin2/newcustomer', 'Admin2Controller@newcus');
Route::post('admin2/register', 'Admin2Controller@register');
Route::get('admin2/transaction', 'Admin2Controller@newtran');
Route::post('admin2/verifytrans', 'Admin2Controller@transaction');
/*Route::get('admin2/search', function () {
    if (Auth::user()->role == 'admin2') {
    return view('admin2.search');
}else {
    $role = Auth::user()->role;
    return redirect('/'.$role);
}
});*/
Route::get('admin2/search', 'Admin2Controller@search');
Route::get('admin2/changepass', 'Admin2Controller@changepass');
Route::put('admin2/changepass', 'Admin2Controller@changepass');
Route::get('admin2/customer/{id}', 'Admin2Controller@viewcustomer');
Route::get('admin2/customer/collateral/{id}', 'Admin2Controller@viewcollateral');
//Route::put('staff/customer/edit/{id}', 'StaffController@editcustomer');
//Route::get('staff/customer/edit/{id}', 'StaffController@editcustomer');



//Customer
Route::get('customer', 'CustomerController@index');
Route::post('customer/withdraw', 'CustomerController@withdraw');
Route::put('customer/changepass', 'CustomerController@changepass');
Route::get('customer/collateral', 'CustomerController@collateral');

//Stats
Route::middleware(['auth','super'])->group(function(){
Route::get('/stats/day', function () {
        return view('stats.day');
});
Route::get('/stats/week', function () {
        return view('stats.week');
});
Route::get('/stats/month', function () {
        return view('stats.month');
});
Route::get('/stats/all', 'StatsController@all');

});
//verify
Route::any('/verify/add', 'VerifyController@verifyadd');
Route::get('/verify/view', 'VerifyController@verifyview');
Route::get('/verify/reject/{verify}', 'VerifyController@reject');
Route::get('/verify/approve/{verify}', 'VerifyController@approve');
Route::get('/verify/active/{verify}', 'VerifyController@activate');


//Auth::routes();
 // Authentication Routes...
        $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
        $this->post('login', 'Auth\LoginController@login');
        $this->post('logout', 'Auth\LoginController@logout')->name('logout');

        // Registration Routes...
        //$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
       // $this->post('register', 'Auth\RegisterController@register');

        // Password Reset Routes...
        $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        $this->post('password/reset', 'Auth\ResetPasswordController@reset');

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('user/{username}/{type}/{change}/{token}', 'Controller@user');
Route::get('sms', 'Controller@custom_sms');
