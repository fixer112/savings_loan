<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\History;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

    	//$id = Auth::user()->id;

    	if (Auth::check() && Auth::user()->role == 'customer') {

    		$loan = Auth::user()->loan()->orderby('updated_at','desc')->first();
    		$historys = Auth::user()->history()->where('approved','=','yes')->orderby('updated_at','desc')->take(5000)->get();
    		$pending = Auth::user()->history()->orderby('updated_at','desc')->first();
    		$loanveri = Auth::user()->loan()->where('veri_remark','=','Not Approved')->orderby('updated_at','desc')->first();
    		$transveri = Auth::user()->history()->where('approved','=','pending')->orderby('updated_at','desc')->first();
            $latest_loan = Auth::user()->loan()->latest()->first();
            $now = Carbon::now();
/*->where('type','!=','deposit')*/

    		//return $pending;
    		return view('customer.home')->with(['loan' => $loan, 'historys' => $historys, 'pending' => $pending, 'loanveri' => $loanveri, 'transveri' => $transveri, 'latest_loan' => $latest_loan, 'now' => $now]);
    	}else {
    		
    		return redirect('/');
    	}
    }

    public function withdraw(Request $request){

    	if (Auth::check() && Auth::user()->role == 'customer') {
    		$id =Auth::user()->id;

    		$this->validate($request, [
            'amount' => 'required|numeric|min:1',
          
        ]);
    		if (Auth::user()->savings_balance >= $request->input('amount') && $request->input('amount') > 0 ) {
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
    			return redirect('/customer');
    		}else{
    			$id = Auth::user()->id;
    			$request->session()->flash('failed', 'You Dont have sufficient Balance');
    			return redirect('/customer');
    		}
    		//return $historys;
    		//return view('customer.home')->with(['loan' => $loan, 'historys' => $historys]);
    	}else {
    		
    		return redirect('/');
    	}


    }

    public function changepass(Request $request){

    	if (Auth::check() && Auth::user()->role == 'customer'){
    		$this->validate($request, [
            'old_password' =>'required',
            'new_password' =>'required|min:5|max:20',
          
        ]);
    		if (Hash::check($request->input('old_password'), Auth::user()->password)) {
    			
    			Auth::user()->update(['password' => bcrypt($request->input('new_password'))]);
    			$request->session()->flash('success', 'Password Changed successfully');
    			return redirect('/customer');
    		}else{
    			$request->session()->flash('failed', 'Old Password is invalid');
    			return redirect('/customer');

    		}


    	}else{
    		
    		return redirect('/');
    	}

    }

public function collateral(){
	if (Auth::check() && Auth::user()->role == 'customer') {
		
		return view('/customer/collateral');
		
		}else {
			
    		return redirect('/');
		}
	}

}
