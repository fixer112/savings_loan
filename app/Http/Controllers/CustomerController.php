<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\History;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

	public function __construct(Request $request)
    {
        if ($request->ajax() || $request->expectsJson()) {
            $this->middleware('auth:api');
        }else{
            $this->middleware('auth');
        }
        //$this->middleware('auth');
    }

    public function index(Request $request){

    	//$id = Auth::user()->id;

    	if (Auth::check() && Auth::user()->role != 'customer') {

            return redirect('/');
        }

        $loan = Auth::user()->loan()->orderby('updated_at','desc')->first();
        $historys = Auth::user()->history()->where('approved','=','yes')->orderby('updated_at','desc')->take(5000)->get();
        $pending = Auth::user()->history()->orderby('updated_at','desc')->first();
        $loanveri = Auth::user()->loan()->where('veri_remark','=','Not Approved')->orderby('updated_at','desc')->first();
        $transveri = Auth::user()->history()->where('approved','=','pending')->orderby('updated_at','desc')->first();
        $latest_loan = Auth::user()->loan()->latest()->first();
        $now = Carbon::now();
        /*->where('type','!=','deposit')*/

    		//return $pending;
        if ($request->ajax() || $request->expectsJson()) {
          if ((isset($loan) && $loan->veri_remark !='pending')) {
            $week_due_date = $latest_loan->week_due_date;
          $due_date = $latest_loan->due_date;
          $skip_due = $latest_loan->skip_due;
          $due = $week_due_date->diffInWeeks($due_date, false) >= 0 ? $week_due_date->diffInWeeks($now, false) + $skip_due : '0';
          }

           $data = ['loan' => $loan, 'historys' => User::find(4)->history()->where('approved','=','yes')->orderby('updated_at','desc')->take(50)->get(), 'pending' => $pending, 'loanveri' => $loanveri, 'transveri' => $transveri, 'latest_loan' => $latest_loan, 'now' => $now, 'user'=> Auth::user(), 'due' => isset($due) ? $due: null, ];

           return \Response::json($data);
       }
       return view('customer.home')->with(['loan' => $loan, 'historys' => $historys, 'pending' => $pending, 'loanveri' => $loanveri, 'transveri' => $transveri, 'latest_loan' => $latest_loan, 'now' => $now]);
    	/*}else {
    		
    		return redirect('/');
    	}*/
    }

    public function withdraw(Request $request){

    	if (Auth::check() && Auth::user()->role != 'customer') {
        if ($request->ajax() || $request->expectsJson()) {

            $data['errors'] = ['fail' => ['Only customer allowed to withdraw']];

              return \Response::json($data);
           }

            return redirect('/');
        }
        $id =Auth::user()->id;

        $this->validate($request, [
            'amount' => 'required|numeric|min:1',

        ]);
        if (Auth::user()->savings_balance >= $request->input('amount') && $request->input('amount') > 0 && !isset($loanveri) && !isset($transveri)) {
    			//$history = new
            History::create([
                'recieved_by' => 'self',
                'description' => 'Withdrwal of '.$request->input('amount').' naira',
                'debit' => $request->input('amount'),
                'credit' => '0',
                'type' => 'withdraw',
                'approved' => 'pending',
                'user_id' => $id,
                'staff_id' =>'self'
            ]);

            $request->session()->flash('success', 'Withdrwal Request successful, wait for admin approval');
            if ($request->ajax() || $request->expectsJson()) {

               $data = ['message' => 'Withdrwal Request successful, wait for admin approval'];

               return \Response::json($data);
           }
           return redirect('/customer');
       }else{
           $id = Auth::user()->id;
           $request->session()->flash('failed', 'You have Insufficient Account Balance has a pending transaction or has an unpaid loan or amount is less than 0');
           if ($request->ajax() || $request->expectsJson()) {

               $data['errors'] = ['fail' => ['You have Insufficient Account Balance has a pending transaction or has an unpaid loan or amount is less than 0']];
               return \Response::json($data, 403);
           }

           return redirect('/customer');
       }
    		//return $historys;
    		//return view('customer.home')->with(['loan' => $loan, 'historys' => $historys]);
    	/*}else {
    		
    		return redirect('/');
    	}*/


    }

    public function changepass(Request $request){

    	if (Auth::check() && Auth::user()->role != 'customer') {

            return redirect('/');
        }
        $this->validate($request, [
            'old_password' =>'required',
            'new_password' =>'required|min:5|max:20',

        ]);
        if (Hash::check($request->input('old_password'), Auth::user()->password)) {

           Auth::user()->update(['password' => bcrypt($request->input('new_password'))]);
           $request->session()->flash('success', 'Password Changed successfully');

           if ($request->ajax() || $request->expectsJson()) {

               $data = ['message' => 'Password Changed successfully'];
               return \Response::json($data);
           }

           return redirect('/customer');
       }else{
           $request->session()->flash('failed', 'Old Password is invalid');
           if ($request->ajax() || $request->expectsJson()) {

               $data['errors'] = ['fail' => ['Old Password is invalid']];
               return \Response::json($data);
           }
           return redirect('/customer');

       }


    	/*}else{
    		
    		return redirect('/');
    	}*/

    }

    public function collateral(Request $request){
     if (Auth::check() && Auth::user()->role != 'customer') {

        return redirect('/');
    }

    if ($request->ajax() || $request->expectsJson()) {

       $data = ['user' => Auth::user()];
       return \Response::json($data);
   }
   return view('/customer/collateral');

		/*}else {
			
    		return redirect('/');
      }*/
  }

}
