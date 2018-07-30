<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\History;
use App\User;
use App\Loan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('staff');
    }

    public function index(Request $request){
    	
    		$id = Auth::user()->id;

    		$approved = History::where('staff_id','=',$id)->where('approved','=','yes')->orderby('updated_at','desc')->take(5000)->get();

    		$pendings = History::where('staff_id','=',$id)->where('approved','=','pending')->orderby('updated_at','desc')->take(5000)->get();

    		$rejected = History::where('staff_id','=',$id)->where('approved','=','no')->orderby('updated_at','desc')->take(5000)->get();

    		$customer = new User;
    		
    		return view('staff.home')->with(['approved' => $approved,'customer' => $customer, 'pendings' => $pendings, 'rejected' => $rejected]);
    	
    }

    public function newcus(){
    	
    	return view('staff.reg');
    	
    }


    public function register(Request $request){
    	
    		$validate = $this->validate($request, [

    	'name' => 'required',

        'mentor' => 'required|numeric|exists:users,mentor',

    	//'email' =>'required|unique:users|email',

    	'username' => 'required|numeric|unique:users',

        'number' => 'required|numeric|unique:users',

    	'password' =>'required|confirmed|min:5|max:20',

    	'passport' => 'required|image|max:100',

    	'idcard' => 'required|image|mimes:jpeg,jpg,png|max:512',

    	'kin_passport' => 'required|image|mimes:jpeg,jpg,png|max:100',

    	'gara1_passport' => 'required|image|mimes:jpeg,jpg,png|max:100',

    	'gara2_passport' => 'required|image|mimes:jpeg,jpg,png|max:100',



    	]);
    		$destination = public_path('/images');

    		$passport = $request->file('passport');
    		$passportname = $request->input('username')."-"."passport".".".$passport->getClientOriginalExtension();

    		$idcard = $request->file('idcard');
    		$idcardname = $request->input('username')."-"."idcard".".".$passport->getClientOriginalExtension();

    		$kin_passport = $request->file('kin_passport');
    		$kin_passportname = $request->input('username')."-"."kin_passport".".".$passport->getClientOriginalExtension();

    		$gara1_passport = $request->file('gara1_passport');
    		$gara1_passportname = $request->input('username')."-"."gara1_passport".".".$passport->getClientOriginalExtension();

    		$gara2_passport = $request->file('gara2_passport');
    		$gara2_passportname = $request->input('username')."-"."gara2_passport".".".$passport->getClientOriginalExtension();

    		$passport->move($destination, $passportname);
    		$idcard->move($destination, $idcardname);
    		$kin_passport->move($destination, $kin_passportname);
    		$gara1_passport->move($destination, $gara1_passportname);
    		$gara2_passport->move($destination, $gara2_passportname);



    	User::create([
            'name' => strtoupper($request->input('name')),
            'username' => $request->input('username'),
            'referal' => $request->input('mentor'),
            'role' => 'customer',
            'password'=> bcrypt($request->input('password')),
            //'email' => $request->input('email'),
            'resi_add' => $request->input('resi_add'),
            'busi_add' => $request->input('busi_add'),
            'nature_add' => $request->input('nature_add'),
            'number' => $request->input('number'),
            'passport' => 'images/'.$passportname,
            'idcard' => 'images/'.$idcardname,
            'kin_name' => strtoupper($request->input('kin_name')),
            'kin_add' => $request->input('kin_add'),
            'kin_relation' => $request->input('kin_relation'),
            'kin_number' => $request->input('kin_number'),
            'kin_verify' => $request->input('kin_verify'),
            'kin_passport' => 'images/'.$kin_passportname,
            'gara1_name' => strtoupper($request->input('gara1_name')),
            'gara1_add' => $request->input('gara1_add'),
            'gara1_occupation' => $request->input('gara1_occupation'),
            'gara1_number' => $request->input('gara1_number'),
            'gara1_verify' => $request->input('gara1_verify'),
            'gara1_passport' => 'images/'.$gara1_passportname,
            'gara2_name' => strtoupper($request->input('gara2_name')),
            'gara2_add' => $request->input('gara2_add'),
            'gara2_occupation' => $request->input('gara2_occupation'),
            'gara2_number' => $request->input('gara2_number'),
            'gara2_verify' => $request->input('gara2_verify'),
            'gara2_passport' => 'images/'.$gara2_passportname,

            
        ]);

         $to = $request->input('number');

        $$message = 'Welcome to HONEYPAYS MICRO CREDIT INVESTMENT LIMITED, your account has successfully been created. Acct. NUmber: ' . $request->input('username') . ' Password: '.$request->input('password').' Click here to change your password mcredit.honeypays.com.ng/login';

        $request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));

        $request->session()->flash('success', 'New Customer created successfully');
        return redirect('staff/newcustomer');

    
    	


    }

    public function newtran(){
    	
    		return view('staff.transaction');

    	


    }

    public function transaction(Request $request){
    	

    	if ($request->input('type') != 'loan') {

        $validate = $this->validate($request, [

        'username' => 'required|numeric|exists:users',

        'amount' => 'required|numeric|min:1',

        'type' => 'required',

        'recieved' => 'required',

        'description' => 'required',

        
        ]);
            }else {

            $validate = $this->validate($request, [

        'username' => 'required|numeric|exists:users',

        'type' => 'required',

        'recieved' => 'required',

        'description' => 'required',

        'due_date' => 'required|date_format:d/m/y|after:yesterday',

        'category' => 'required',

        ]);
            }
    			$user = User::where('username','=', $request->input('username'))->where('suspend', '=', 'no')->first();
    			$loan = $user->loan()->where('veri_remark','=','Not Approved')->orderby('updated_at','desc')->first();
    			$pending = $user->history()->where('approved','=','pending')->orderby('updated_at','desc')->first();
                $latest_loan = $user->loan()->latest()->first();



    			if ($user && $user->role == 'customer'){
    				
    			

    			if ($request->input('type')=='withdraw') {
    				if ($user->savings_balance >= $request->input('amount') && $request->input('amount') > 0 && !isset($loan) && !isset($pending) ) {
    					
    				

			    				History::create([
			            'recieved_by' => $request->input('recieved'),
			            'description' => $request->input('description'),
			            'debit' => $request->input('amount'),
			            'credit' => '0',
			            'type' => $request->input('type'),
			            'approved' => 'pending',
			            'user_id' => $user->id,
			            'staff_id' => Auth::user()->id,
			        ]);
			    		$request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username').' awaiting approval from admin');
			    		//return $pending."</br>".$loan;
    					return redirect('staff/transaction');

			    			}else {
			    			$request->session()->flash('failed', $request->input('username').' has Insufficient Account Balance or has a pending transaction or has an unpaid loan ');
			    			//return $pending."</br>".$loan;
			    			return redirect('staff/transaction');
			    			}

			    		}


    			if ($request->input('type')=='deposit'){
    				if (!isset($pending)) {
    				History::create([
			            'recieved_by' => $request->input('recieved'),
			            'description' => $request->input('description'),
			            'debit' => '0',
			            'credit' => $request->input('amount'),
			            'type' => $request->input('type'),
			            'approved' => 'pending',
			            'user_id' => $user->id,
			            'staff_id' => Auth::user()->id,
			        ]);
			        $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username').' awaiting approval from admin');
    					return redirect('staff/transaction');
    					}else {
    						$request->session()->flash('failed', $request->input('username').'  has a pending transaction awaiting approval');
			    			//return $pending."</br>".$loan;
			    			return redirect('staff/transaction');
    					}
    				
    				}
    			

    			if ($request->input('type')=='loan') {


    				if ( !isset($loan) && !isset($pending)) {

                        if ($latest_loan) {
                        
                    

                        if($latest_loan->due == 'paid' && $latest_loan->interest_status == 'paid'){

    					History::create([
			            'recieved_by' => $request->input('recieved'),
			            'description' => $request->input('description'),
			            'debit' => $request->input('category'),
			            'credit' => '0',
			            'type' => $request->input('type'),
			            'approved' => 'pending',
			            'user_id' => $user->id,
			            'staff_id' => Auth::user()->id,
			        ]);

    					Loan::create([
			            'due_date' => Carbon::createFromFormat('m/d/y', $request->input('due_date')),
			            'veri_remark' => 'pending',
			            'loan_category' => $request->input('category'),
			            'user_id' => $user->id,
			            'staff_id' => Auth::user()->id,
                        'week_due_date' => Carbon::now(),
			            
			        ]);

			        $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username').' awaiting approval from admin');
    					return redirect('staff/transaction');
                    }else {
                        $request->session()->flash('failed', $request->input('username').' has unpaid interest fee or default fee');
                        return redirect('staff/transaction');
                    }

                }else {
                    History::create([
                        'recieved_by' => $request->input('recieved'),
                        'description' => $request->input('description'),
                        'debit' => $request->input('category'),
                        'credit' => '0',
                        'type' => $request->input('type'),
                        'approved' => 'pending',
                        'user_id' => $user->id,
                        'staff_id' => Auth::user()->id,
                    ]);

                        Loan::create([
                        'due_date' => Carbon::createFromFormat('d/m/y', $request->input('due_date')),
                        'veri_remark' => 'pending',
                        'loan_category' => $request->input('category'),
                        'user_id' => $user->id,
                        'staff_id' => Auth::user()->id,
                        'week_due_date' => Carbon::now(),
                        
                    ]);

                    $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username').' awaiting approval from admin');
                        return redirect('staff/transaction');
                }
    					
    				}else{
    					$request->session()->flash('failed', $request->input('username').' has a pending transaction or has an unpaid loan');
			    		return redirect('staff/transaction');
    				}
    				
    			}

                if ($request->input('type') =='default_fee') {
                    if ($latest_loan) {
                        
                    
                    if ($user->savings_balance > 0 && $user->savings_balance > $request->input('amount') && $latest_loan->week_due_date->diffInWeeks($latest_loan->due_date) > 0) {

                        History::create([
                        'recieved_by' => $request->input('recieved'),
                        'description' => $request->input('description'),
                        'debit' => $request->input('amount'),
                        'credit' => '0',
                        'type' => $request->input('type'),
                        'approved' => 'pending',
                        'user_id' => $user->id,
                        'staff_id' => Auth::user()->id,
                    ]);

                        
                        
                    }else {
                        $request->session()->flash('failed', $request->input('username').' has Insufficient fund or due paid');
                        return redirect('staff/transaction');
                    }
                    
                    }else {
                        $request->session()->flash('failed', $request->input('username').' has no loan history');
                        return redirect('staff/transaction');
                    }
                }


                if ($request->input('type') =='interest_fee') {
                    if ($latest_loan) {
                        
                    

                if ($user->savings_balance > 0 && $user->savings_balance > $request->input('amount') && $loan->interest_status != 'paid' ) {

                    History::create([
                        'recieved_by' => $request->input('recieved'),
                        'description' => $request->input('description'),
                        'debit' => $request->input('amount'),
                        'credit' => '0',
                        'type' => $request->input('type'),
                        'approved' => 'pending',
                        'user_id' => $user->id,
                        'staff_id' => Auth::user()->id,
                    ]);


                    }else {
                    $request->session()->flash('failed', $request->input('username').' has Insufficient fund or interest already paid');
                    return redirect('staff/transaction');
                    }
                    }else {
                        $request->session()->flash('failed', $request->input('username').' has no loan history');
                        return redirect('staff/transaction');
                    }
                    
                }

    		}else{
    			$request->session()->flash('failed', $request->input('username').' is not a customer or has been suspended');
			    return redirect('staff/transaction');
    		}

    		
    }

    	public function changepass(Request $request){
    		

    			if (!$request->isMethod('put')) {
    				return view('staff.changepass');
    				
    			}else {
    				$this->validate($request, [
            'old_password' =>'required',
            'new_password' =>'required|min:5|max:20',
          
        ]);
    		if (Hash::check($request->input('old_password'), Auth::user()->password)) {
    			
    			Auth::user()->update(['password' => bcrypt($request->input('new_password'))]);
    			$request->session()->flash('success', 'Password Changed successfully');
    			return redirect('/staff/changepass');
    		}else{
    			$request->session()->flash('failed', 'Old Password is invalid');
    			return redirect('/staff/changepass');

    		}
    			}

    	
    }

    public function search(Request $request){

    //$keyword = $request->input('search');

    $searchs = User::where('role', '=', 'customer')->get();

    /*User::where(function ($query) use ($keyword) {
            $query->where('role', '=', 'customer')->where('suspend', '=', 'no');
        })->where(function ($query) use ($keyword) {
        $query->where('username', 'LIKE', '%'.$keyword.'%')
        //->orWhere('email', 'LIKE', '%'.$keyword.'%')
        ->orWhere('number', 'LIKE', '%'.$keyword.'%');

        })->get();*/

    //$request->session()->put('search', $keyword);
    
    return view('staff.search', compact('searchs'));
    }


    public function viewcustomer($id){

    	$user = User::where('id','=', $id)->where('suspend', '=', 'no')->first();
    	if (Auth::check() && Auth::user()->role == 'staff' && $user) {
    		$loan = $user->loan()->orderby('updated_at','desc')->first();
    		$historys = $user->history()->where('approved','=','yes')->orderby('updated_at','desc')->take(5000)->get();
    		$rejected = $user->history()->where('approved','=','no')->orderby('updated_at','desc')->take(5000)->get();
            $latest_loan = $user->loan()->latest()->first();
            $now = Carbon::now();

    		if ($user && $user->role == 'customer') {
    		 	
    		 	return view('staff.viewcustomer')->with(['user' => $user, 'loan' => $loan, 'historys' =>$historys, 'rejected' => $rejected, 'latest_loan' => $latest_loan, 'now' => $now]);
    		 }else {
    		 	return 'You can only view Customer page or customer suspended';
    		 }
    	}	
    	
    }

    public function viewcollateral($id){
    	$user = User::where('id','=', $id)->where('suspend', '=', 'no')->first();
    	if (Auth::check() && Auth::user()->role == 'staff' && $user) {

    		return view('staff.viewcollateral')->with(['user' => $user]);
        }
    }

    public function editcustomer(Request $request, $id){
    	$user = User::where('id','=', $id)->where('suspend', '=', 'no')->first();

    	if (Auth::check() && Auth::user()->role == 'staff' && $user) {

    		if ($request->isMethod('get')) {
    			return view('staff.editcustomer')->with(['user' => $user]);
    			
    		}elseif ($request->isMethod('put') && $request->input('number')) {

    			$this->validate($request, [

	    	'number' => 'required|numeric|unique:users',


	    	 ]);
    		$user->update([
    			'number' => $request->input('number'),
    			]);
    		$request->session()->flash('success', $request->input('name').' Mobile number updated successfully.');
        	return redirect('/staff/customer/edit/'.$id)->with(['user' => $user]);
    			
    		}elseif ($request->isMethod('put') && $request->input('password')) {
    			$this->validate($request, [

	    	'password' =>'required|min:5|max:20',

	    	 ]);
    		 $user->update([
    			'password'=> bcrypt($request->input('password')),
    			]);
    		$request->session()->flash('success', $request->input('name').' Password updated successfully.');
        	return redirect('/staff/customer/edit/'.$id)->with(['user' => $user]);
    			
    		}elseif ($request->isMethod('put') && !$request->input('number') && !$request->input('password')) {

    			$this->validate($request, [

	        //'number' => 'numeric|unique:users',

	    	//'password' =>'required|min:5|max:20',

	    	'passport' => 'image|max:100',

	    	'idcard' => 'image|mimes:jpeg,jpg,png|max:512',

	    	'kin_passport' => 'image|mimes:jpeg,jpg,png|max:100',

	    	'gara1_passport' => 'image|mimes:jpeg,jpg,png|max:100',

	    	'gara2_passport' => 'image|mimes:jpeg,jpg,png|max:100',

	    	 ]);

    		$destination = public_path('/images');

    		if ($request->file('passport')) {
    			$passport = $request->file('passport');
    		$passportname = $user->username."-"."passport".".".$passport->getClientOriginalExtension();
    		$passport->move($destination, $passportname);
    		}

    		
    		if ($request->file('idcard')) {
    			$idcard = $request->file('idcard');
    		$idcardname = $user->username."-"."idcard".".".$passport->getClientOriginalExtension();
    		$idcard->move($destination, $idcardname);
    		}

    		
    		if ($request->file('kin_passport')) {
    			$kin_passport = $request->file('kin_passport');
    		$kin_passportname = $user->username."-"."kin_passport".".".$passport->getClientOriginalExtension();
    		$kin_passport->move($destination, $kin_passportname);
    		}

    		
    		if ($request->file('gara1_passport')) {
    			$gara1_passport = $request->file('gara1_passport');
    		$gara1_passportname = $user->username."-"."gara1_passport".".".$passport->getClientOriginalExtension();
    		$gara1_passport->move($destination, $gara1_passportname);
    		}

    		
    		if ($request->file('gara2_passport')) {
    			$gara2_passport = $request->file('gara2_passport');
    		$gara2_passportname = $user->usernames."-"."gara2_passport".".".$passport->getClientOriginalExtension();
    		$gara2_passport->move($destination, $gara2_passportname);
    		}
    		


    		$user->update([
    		'name' => strtoupper($request->input('name')),
            //'username' => $request->input('username'),
            //'password'=> bcrypt($request->input('password')),
            'resi_add' => $request->input('resi_add'),
            'busi_add' => $request->input('busi_add'),
            'nature_add' => $request->input('nature_add'),
            //'number' => $request->input('number'),
            'kin_name' => strtoupper($request->input('kin_name')),
            'kin_add' => $request->input('kin_add'),
            'kin_relation' => $request->input('kin_relation'),
            'kin_number' => $request->input('kin_number'),
            'kin_verify' => $request->input('kin_verify'),
            'gara1_name' => strtoupper($request->input('gara1_name')),
            'gara1_add' => $request->input('gara1_add'),
            'gara1_occupation' => $request->input('gara1_occupation'),
            'gara1_number' => $request->input('gara1_number'),
            'gara1_verify' => $request->input('gara1_verify'),
            'gara2_name' => strtoupper($request->input('gara2_name')),
            'gara2_add' => $request->input('gara2_add'),
            'gara2_occupation' => $request->input('gara2_occupation'),
            'gara2_number' => $request->input('gara2_number'),
            'gara2_verify' => $request->input('gara2_verify'),
    			]);
    		$request->session()->flash('success', $request->input('name').' Updated successfully');
        	return redirect('/staff/customer/edit/'.$id)->with(['user' => $user]);
    		}


    	}

    }


}