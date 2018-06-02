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

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
    	if (Auth::check() && Auth::user()->role == 'admin') {
    		//$id = Auth::user()->id;

    		$approved = History::where('approved','=','yes')->orderby('updated_at','desc')->take(5000)->get();

    		$pendings = History::where('approved','=','pending')->orderby('updated_at','desc')->take(5000)->get();

    		$rejected = History::where('approved','=','no')->orderby('updated_at','desc')->take(5000)->get();

    		$customer = new User;

    		
    		return view('admin.home')->with(['approved' => $approved,'customer' => $customer, 'pendings' => $pendings, 'rejected' => $rejected]);
    	}else {
    		
    		return redirect('/');
    	}
    }

    public function newcus(){
    	if (Auth::check() && Auth::user()->role == 'admin') {
    	return view('admin.reg');
    	}else{
    		
    		return redirect('/');
    	}
    }


    public function register(Request $request){
    	if (Auth::check() && Auth::user()->role == 'admin') {
    		$validate = $this->validate($request, [

    	'name' => 'required',

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
    		$idcardname = $request->input('username')."-"."idcard".".".$idcard->getClientOriginalExtension();

    		$kin_passport = $request->file('kin_passport');
    		$kin_passportname = $request->input('username')."-"."kin_passport".".".$kin_passport->getClientOriginalExtension();

    		$gara1_passport = $request->file('gara1_passport');
    		$gara1_passportname = $request->input('username')."-"."gara1_passport".".".$gara1_passport->getClientOriginalExtension();

    		$gara2_passport = $request->file('gara2_passport');
    		$gara2_passportname = $request->input('username')."-"."gara2_passport".".".$gara2_passport->getClientOriginalExtension();

    		$passport->move($destination, $passportname);
    		$idcard->move($destination, $idcardname);
    		$kin_passport->move($destination, $kin_passportname);
    		$gara1_passport->move($destination, $gara1_passportname);
    		$gara2_passport->move($destination, $gara2_passportname);



    	User::create([
            'name' => strtoupper($request->input('name')),
            'username' => $request->input('username'),
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

    	$message = 'Welcome to HONEYPAYS MICRO CREDIT INVESTMENT LIMITED, your account has successfully been created. Acct. NUmber: ' . $request->input('username') . ' Password: '.$request->input('password').' Click here to change your password mcredit.honeypays.com.ng/login';

    	$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));
			        
        $request->session()->flash('success', 'New Customer created successfully');
        return redirect('admin/newcustomer');

    
    	}else {
    		
    		return redirect('/');
    	}


    }

    public function newtran(){
    	if (Auth::check() && Auth::user()->role == 'admin') {
    		return view('admin.transaction');

    	}else{
    		
    		return redirect('/');
    	}


    }

    public function approve(Request $request, $pendingid){
    	if (Auth::check() && Auth::user()->role == 'admin') {

    	$history = History::where('id', '=', $pendingid)->first();
    	$user = /*$history->user()->first();*/ User::where('id', '=', $history->user_id)->first();
    	$loan = $user->loan()->where('veri_remark','=','pending')->orderby('updated_at','desc')->first();
        $now = Carbon::now();

    	if ($history && $history->approved == 'pending') {

    			$to = $user->number;
		    	setlocale(LC_MONETARY, 'en_NG');
		    	
		    	
    	if ($history->type == 'withdraw') {
		//update balance
		$balance = $user->savings_balance - $history->debit;
		$user->update(['savings_balance' => $balance]);

		$history->update(['approved' => 'yes']);

				$debit = $this->naira($history->debit);
		    	$credit = $this->naira($history->credit);
		    	$savings_balance = $this->naira($user->savings_balance);
		    	$loan_balance = $this->naira($user->loan_balance);

		//Alert User by sms
		$message = 'Acct: '.$user->username.' Transaction Type: '.$history->description.' Transaction Amt: '.$debit.' Avail Savings Bal: '.$savings_balance.' Loan Bal: '.$loan_balance;

		$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));

		$request->session()->flash('success', $history->type.' successfully applied for '.$user->username);
		return redirect('admin');
    	}

    	if ($history->type == 'deposit') {
    		if ($user->loan_balance > 0 && $user->loan_balance <= $history->credit) {
    					
    					$remain = $history->credit - $user->loan_balance;
    					$user->loan_balance = 0;
    					$balance = $user->savings_balance + $remain;
    					$user->update(['savings_balance' => $balance]);
    					//update loan verify
    					if ($loan) {
    						$loan->update(['veri_remark' => 'Approved']);
    					}

                        if ($latest_loan && $latest_loan->week_due_date->diffInWeeks($now, false) > 0 && $latest_loan->week_due_date->diffInWeeks($latest_loan->due_date, false) > 0 ) {

                            $skip_due = $latest_loan->skip_due + $latest_loan->week_due_date->diffInWeeks($now, false);

                            $latest_loan->update(['skip_due' => $skip_due, 'week_due_date' => $now] );
                            
                        }
    					
    			$debit = $this->naira($history->debit);
		    	$credit = $this->naira($history->credit);
		    	$savings_balance = $this->naira($user->savings_balance);
		    	$loan_balance = $this->naira($user->loan_balance);
				//Alert User by SMS
				$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: '.$user->username.' Your loan have been fully paid. Transaction Type: '.$history->description.' Transaction Amt: '.$credit.' Avail Savings Bal: '.$savings_balance.' Loan Bal: '.$loan_balance;

	    		$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


    				}elseif ($user->loan_balance > $history->credit) {
    					$balance = $user->loan_balance - $history->credit;
    					$user->update(['loan_balance' => $balance]);

    					$debit = $this->naira($history->debit);
		    	$credit = $this->naira($history->credit);
		    	$savings_balance = $this->naira($user->savings_balance);
		    	$loan_balance = $this->naira($user->loan_balance);

    			$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: '.$user->username.' Loan partly paid. Transaction Type: '.$history->description.' Transaction Amt: '.$credit.' Avail Savings Bal: '.$savings_balance.' Loan Bal: '.$loan_balance;

    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));

    				}elseif($user->loan_balance == 0) {
    					$balance = $user->savings_balance + $history->credit;
    					$user->update(['savings_balance' => $balance]);

    			$debit = $this->naira($history->debit);
		    	$credit = $this->naira($history->credit);
		    	$savings_balance = $this->naira($user->savings_balance);
		    	$loan_balance = $this->naira($user->loan_balance);

    			$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: '.$user->username.' Transaction Type: '.$history->description.' Transaction Amt: '.$credit.' Avail Savings Bal: '.$savings_balance.' Loan Bal: '.$loan_balance;

    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));
    				}

    				$history->update(['approved' => 'yes']);

    			$request->session()->flash('success', $history->type.' successfully applied for '.$user->username);
				return redirect('admin');
    	}
    	if ($history->type == 'loan') {
    		//update loan balance
    		$user->update(['loan_balance' => $history->debit]);
    		$loan->update(['veri_remark' => 'Not Approved']);

    		$history->update(['approved' => 'yes']);

    			$debit = $this->naira($history->debit);
		    	$credit = $this->naira($history->credit);
		    	$savings_balance = $this->naira($user->savings_balance);
		    	$loan_balance = $this->naira($user->loan_balance);

    		$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: '.$user->username.' Loan Application Approved Transaction Type: '.$history->description.' Transaction Amt: '.$debit.' Avail Savings Bal: '.$savings_balance.' Loan Bal: '.$loan_balance;

    			$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));

    		$request->session()->flash('success', $history->type.' successfully applied for '.$user->username);
			return redirect('admin');
    		}

    	if ($history->type == 'default_fee') {

    		$balance = $user->savings_balance - $history->debit;

    					$user->update(['savings_balance' => $balance]);
    					
    						$loan->update(['week_due_date' => Carbon::now()]);

    					if ($latest_loan->week_due_date->diffInWeeks($now) <= 0 && $latest_loan->week_due_date->diffInWeeks($latest_loan->due_date, false) <= 0) {

                            $latest_loan->update(['due' => 'paid']);
                        }
    					
    					$debit = $this->naira($history->debit);
		    	$credit = $this->naira($history->credit);
		    	$savings_balance = $this->naira($user->savings_balance);
		    	$loan_balance = $this->naira($user->loan_balance);
				    	

    					$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: ' . $user->username . ' Transaction Type: ' . $request->input('description') . ' Transaction Amt: ' . $debit . ' Avail Savings Bal: ' . $savings_balance . ' Loan Bal: ' . $loan_balance . ' HoneyPays | TrulyPays';

    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


			        $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));
    					return redirect('admin');
    		
    		}

    		if ($history->type == 'interest_fee') {
    			$balance = $user->savings_balance - $history->debit;

    				$user->update(['savings_balance' => $balance]);

    				$debit = $this->naira($history->debit);
		    	$credit = $this->naira($history->credit);
		    	$savings_balance = $this->naira($user->savings_balance);
		    	$loan_balance = $this->naira($user->loan_balance);

				    	
				    		$loan->update(['interest_status' => 'paid']);
				    	

    					$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: ' . $user->username . ' Transaction Type: ' . $request->input('description') . ' Transaction Amt: ' . $debit . ' Avail Savings Bal: ' . $savings_balance . ' Loan Bal: ' . $loan_balance . ' HoneyPays | TrulyPays';

    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


			        $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));
    					return redirect('admin/transaction');
    			
    		}

    	}else {
    	$request->session()->flash('failed', 'Transaction not found or transaction is not pending');
		return redirect('admin');
    	}

    }else{
    		
    		return redirect('/');
    	}

    }

    public function reject(Request $request, $pendingid){
    	if (Auth::check() && Auth::user()->role == 'admin') {

    	$history = History::where('id', '=', $pendingid)->first();
    	$user = /*$history->user()->first();*/ User::where('id', '=', $history->user_id)->first();
    	$loan = $user->loan()->where('veri_remark','=','pending')->orderby('updated_at','desc')->first();

    	if ($history && $history->approved == 'pending') {

    		$history->update(['approved' => 'no']);

    	$request->session()->flash('success', 'Transaction rejected successfully');
		return redirect('admin');
    	
    	}else {
    	$request->session()->flash('failed', 'Transaction not found or transaction is not pending');
		return redirect('admin');
    	}

    }else{
    		
    		return redirect('/');
    	}

    }
    

    public function transaction(Request $request){
    	if (Auth::check() && Auth::user()->role == 'admin') {

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

    			$user = User::where('username','=', $request->input('username'))->first();
    			$loan = $user->loan()->where('veri_remark','=','Not Approved')->orderby('updated_at','desc')->first();
    			$pending = $user->history()->where('approved','=','pending')->orderby('updated_at','desc')->first();
    			$latest_loan = $user->loan()->latest()->first();
                $now = Carbon::now();

    			
		    	$to = $user->number;
		    	setlocale(LC_MONETARY, 'en_NG');
		    	



    			if ($user->role == 'customer'){
    				
    			

    			if ($request->input('type')=='withdraw') {
    				if ($user->savings_balance >= $request->input('amount') && $request->input('amount') > 0 && !isset($loan) && !isset($pending) ) {
    					
    				

			    				History::create([
			            'recieved_by' => $request->input('recieved'),
			            'description' => $request->input('description'),
			            'debit' => $request->input('amount'),
			            'credit' => '0',
			            'type' => $request->input('type'),
			            'approved' => 'yes',
			            'user_id' => $user->id,
			        ]);
			    		$request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));

			    		//update balance
			    		$balance = $user->savings_balance - $request->input('amount');
			    		$user->update(['savings_balance' => $balance]);

			    		$amount = $this->naira($request->input('amount'));
				    	$savings_balance = $this->naira($user->savings_balance);
				    	$loan_balance = $this->naira($user->loan_balance);
				    	$loan_amount = $this->naira($request->input('category'));

			    		//Alert User by sms
			    		$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: '.$user->username.' Transaction Type: '.$request->input('description').' Transaction Amt: '.$amount.' Avail Savings Bal: '.$savings_balance.' Loan Bal: '.$loan_balance;

			    		$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));
			    		
    					return redirect('admin/transaction');

			    			}else {
			    			$request->session()->flash('failed', $request->input('username').' has Insufficient Account Balance or has a pending transaction or has an unpaid loan or amount is less than 0');
			    			
			    			return redirect('admin/transaction');
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
			            'approved' => 'yes',
			            'user_id' => $user->id,
			        ]);


    				if ($user->loan_balance > 0 && $user->loan_balance <= $request->input('amount')) {

    					if ($latest_loan && $latest_loan->week_due_date->diffInWeeks($now, false) > 0 && $latest_loan->week_due_date->diffInWeeks($latest_loan->due_date, false) > 0 ) {

                            $skip_due = $latest_loan->skip_due + $latest_loan->week_due_date->diffInWeeks($now, false);

                            $latest_loan->update(['skip_due' => $skip_due, 'week_due_date' => $now ]);
                            
                        }


    					$remain = $request->input('amount') - $user->loan_balance;
    					$user->loan_balance = 0;
    					$balance = $user->savings_balance + $remain;
    					$user->update(['savings_balance' => $balance]);
    					//update loan verify
    					if ($loan) {
    						$loan->update(['veri_remark' => 'Approved']);
    					}


    					$amount = $this->naira($request->input('amount'));
				    	$savings_balance = $this->naira($user->savings_balance);
				    	$loan_balance = $this->naira($user->loan_balance);
				    	$loan_amount = $this->naira($request->input('category'));

    					//Alert User by SMS
    					$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: '.$user->username.' Your loan have been fully paid. Transaction Type: '.$request->input('description').' Transaction Amt: '.$amount.' Avail Savings Bal: '.$savings_balance.' Loan Bal: '.$loan_balance;

			    		$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));

    				}elseif ($user->loan_balance > $request->input('amount')) {
    					$balance = $user->loan_balance - $request->input('amount');
    					$user->update(['loan_balance' => $balance]);

    					$amount = $this->naira($request->input('amount'));
				    	$savings_balance = $this->naira($user->savings_balance);
				    	$loan_balance = $this->naira($user->loan_balance);
				    	$loan_amount = $this->naira($request->input('category'));

    					$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: '.$user->username.' Loan partly paid. Transaction Type: '.$request->input('description').' Transaction Amt: '.$amount.' Avail Savings Bal: '.$savings_balance.' Loan Bal: '.$loan_balance;

    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


    				}elseif($user->loan_balance == 0) {
    					$balance =$user->savings_balance + $request->input('amount');
    					$user->update(['savings_balance' => $balance]);

    					$amount = $this->naira($request->input('amount'));
				    	$savings_balance = $this->naira($user->savings_balance);
				    	$loan_balance = $this->naira($user->loan_balance);
				    	$loan_amount = $this->naira($request->input('category'));

    					$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: '.$user->username.' Transaction Type: '.$request->input('description').' Transaction Amt: '.$amount.' Avail Savings Bal: '.$savings_balance.' Loan Bal: '.$loan_balance;

    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));
    				}

    				//}
			        $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));
			        //return $pending."</br>".$loan;
    					return redirect('admin/transaction');
    					}else {
    						$request->session()->flash('failed', $request->input('username').'  has a pending transaction awaiting approval');
			    			// return $pending."</br>".$loan;
			    			return redirect('admin/transaction');
    					}
    				
    				
    			}
    			

    			if ($request->input('type') =='loan') {


		    				
    				if ( !isset($loan) && !isset($pending)) {

                        if ($latest_loan) {

    					if ( $latest_loan->due == 'paid' && $latest_loan->interest_status == 'paid' ) {
    						
    					

    					History::create([
			            'recieved_by' => $request->input('recieved'),
			            'description' => $request->input('description'),
			            'debit' => $request->input('category'),
			            'credit' => '0',
			            'type' => $request->input('type'),
			            'approved' => 'yes',
			            'user_id' => $user->id,
			        ]);
    					//update loan balance
    					$user->update(['loan_balance' => $request->input('category')]);
    					//$date = DateTime::createFromFormat($format, $date);

    					Loan::create([
			            'due_date' => Carbon::createFromFormat('d/m/y', $request->input('due_date')),
			            'veri_remark' => 'Not Approved',
			            'loan_category' => $request->input('category'),
			            'user_id' => $user->id,
			            'week_due_date' => Carbon::now(),
			            
			        ]);

    					$amount = $this->naira($request->input('amount'));
				    	$savings_balance = $this->naira($user->savings_balance);
				    	$loan_balance = $this->naira($user->loan_balance);
				    	$loan_amount = $this->naira($request->input('category'));

    					$message = 'NOTIFICATIOjnN ' .Carbon::now(). ' Acct: ' . $user->username . ' Loan Application Approved Transaction Type: ' . $request->input('description') . ' Transaction Amt: ' . $loan_amount . ' Avail Savings Bal: ' . $savings_balance . ' Loan Bal: ' . $loan_balance . ' HoneyPays | TrulyPays';

    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


			        $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));
    					return redirect('admin/transaction');

    				}else {
    					$request->session()->flash('failed', $request->input('username').' has unpaid interest fee or default fee');
			    		return redirect('admin/transaction');
			    	}

                }else {
                    History::create([
                        'recieved_by' => $request->input('recieved'),
                        'description' => $request->input('description'),
                        'debit' => $request->input('category'),
                        'credit' => '0',
                        'type' => $request->input('type'),
                        'approved' => 'yes',
                        'user_id' => $user->id,
                    ]);
                        //update loan balance
                        $user->update(['loan_balance' => $request->input('category')]);
                        //$date = DateTime::createFromFormat($format, $date);

                        Loan::create([
                        'due_date' => Carbon::createFromFormat('d/m/y', $request->input('due_date')),
                        'veri_remark' => 'Not Approved',
                        'loan_category' => $request->input('category'),
                        'user_id' => $user->id,
                        'week_due_date' => Carbon::now(),
                        
                    ]);

                        $amount = $this->naira($request->input('amount'));
                        $savings_balance = $this->naira($user->savings_balance);
                        $loan_balance = $this->naira($user->loan_balance);
                        $loan_amount = $this->naira($request->input('category'));

                        $message = 'NOTIFICATION ' .Carbon::now(). ' Acct: ' . $user->username . ' Loan Application Approved Transaction Type: ' . $request->input('description') . ' Transaction Amt: ' . $loan_amount . ' Avail Savings Bal: ' . $savings_balance . ' Loan Bal: ' . $loan_balance . ' HoneyPays | TrulyPays';

                        $request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


                    $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));
                        return redirect('admin/transaction');
                }
    					
    				}else {
    					$request->session()->flash('failed', $request->input('username').' has a pending transaction or has an unpaid loan');
			    		return redirect('admin/transaction');
    				}
    				
    			
    		}

    			if ($request->input('type') =='default_fee') {
                    if ($latest_loan) {

                       // $now = Carbon::now();
                    
    				if ($user->savings_balance > 0 && $user->savings_balance > $request->input('amount')) {

    					History::create([
			            'recieved_by' => $request->input('recieved'),
			            'description' => $request->input('description'),
			            'debit' => $request->input('amount'),
			            'credit' => '0',
			            'type' => $request->input('type'),
			            'approved' => 'yes',
			            'user_id' => $user->id,
			        ]);

    					$balance = $user->savings_balance - $request->input('amount');

    					$user->update(['savings_balance' => $balance]);
    					
    					$latest_loan->update(['week_due_date' => Carbon::now()]);

                        if ($latest_loan->week_due_date->diffInWeeks($now, false) <= 0 && $latest_loan->week_due_date->diffInWeeks($latest_loan->due_date, false) <= 0) {

                            $latest_loan->update(['due' => 'paid']);
                        }
    					
    					
    					$amount = $this->naira($request->input('amount'));
				    	$savings_balance = $this->naira($user->savings_balance);
				    	$loan_balance = $this->naira($user->loan_balance);
				    	

    					$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: ' . $user->username . ' Transaction Type: ' . $request->input('description') . ' Transaction Amt: ' . $amount . ' Avail Savings Bal: ' . $savings_balance . ' Loan Bal: ' . $loan_balance . ' HoneyPays | TrulyPays';

    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


			        $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));
    					return redirect('admin/transaction');
                    
    					
    				}else {
    					$request->session()->flash('failed', $request->input('username').' has Insufficient fund.');
			    		return redirect('admin/transaction');
    				}

                    }else {
                        $request->session()->flash('failed', $request->input('username').' has no loan history');
                        return redirect('admin/transaction');
                    }
    				
    			}

    			if ($request->input('type') =='interest_fee') {

                    if ($latest_loan) {
                        
                    

    			if ($user->savings_balance > 0 && $user->savings_balance > $request->input('amount') && $latest_loan->interest_status != 'paid' ) {

    				History::create([
			            'recieved_by' => $request->input('recieved'),
			            'description' => $request->input('description'),
			            'debit' => $request->input('amount'),
			            'credit' => '0',
			            'type' => $request->input('type'),
			            'approved' => 'yes',
			            'user_id' => $user->id,
			        ]);

    				$balance = $user->savings_balance - $request->input('amount');

    				$user->update(['savings_balance' => $balance]);

    				$amount = $this->naira($request->input('amount'));
				    	$savings_balance = $this->naira($user->savings_balance);
				    	$loan_balance = $this->naira($user->loan_balance);

				    	
				    		$latest_loan->update(['interest_status' => 'paid']);
				    	

    					$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: ' . $user->username . ' Transaction Type: ' . $request->input('description') . ' Transaction Amt: ' . $amount . ' Avail Savings Bal: ' . $savings_balance . ' Loan Bal: ' . $loan_balance . ' HoneyPays | TrulyPays';

    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


			        $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));
    					return redirect('admin/transaction');


    				}else {
    				$request->session()->flash('failed', $request->input('username').' has Insufficient fund or interest already paid');
			    	return redirect('admin/transaction');
    				}

    				}else {
                        $request->session()->flash('failed', $request->input('username').' has no loan history');
                        return redirect('admin/transaction');
                    }
    			}

    		}else{
    			$request->session()->flash('failed', $request->input('username').' is not a customer');
			    return redirect('admin/transaction');
    		}

    		}else{
    			
    		return redirect('/');
    		}
    }



    	public function changepass(Request $request){
    		if (Auth::check() && Auth::user()->role == 'admin') {

    			if (!$request->isMethod('put')) {
    				return view('admin.changepass');
    				
    			}else {
    				$this->validate($request, [
            'old_password' =>'required',
            'new_password' =>'required|min:5|max:20',
          
        ]);
    		if (Hash::check($request->input('old_password'), Auth::user()->password)) {
    			
    			Auth::user()->update(['password' => bcrypt($request->input('new_password'))]);
    			$request->session()->flash('success', 'Password Changed successfully');
    			return redirect('/admin/changepass');
    		}else{
    			$request->session()->flash('failed', 'Old Password is invalid');
    			return redirect('/admin/changepass');

    		}
    			}

    		}else {
    			
    		return redirect('/');
    		}


    	}

    public function searchstaff(Request $request){

        if (Auth::check() && Auth::user()->role == 'admin') {

    $keyword = $request->input('search');

    $searchs = User::where(function ($query) use ($keyword) {
            $query->where('role', '=', 'staff')->orWhere('role', '=', 'admin2');
        })->where(function ($query) use ($keyword) {
        $query->where('username', 'LIKE', '%'.$keyword.'%')
        //->orWhere('email', 'LIKE', '%'.$keyword.'%')
        ->orWhere('number', 'LIKE', '%'.$keyword.'%');

        })->get();

    $request->session()->put('search', $keyword);
    return view('admin.searchstaff')->with(['searchs' => $searchs]);
        }else {
            
            return redirect('/');
        }
    }

     public function search(Request $request){

        if (Auth::check() && Auth::user()->role == 'admin') {
    $keyword = $request->input('search');

    $searchs = User::where(function ($query) use ($keyword) {
            $query->where('role', '=', 'customer');
        })->where(function ($query) use ($keyword) {
        $query->where('username', 'LIKE', '%'.$keyword.'%')
        //->orWhere('email', 'LIKE', '%'.$keyword.'%')
        ->orWhere('number', 'LIKE', '%'.$keyword.'%');

        })->get();

    $request->session()->put('search', $keyword);
    return view('admin.search')->with(['searchs' => $searchs]);
        }else {
            
            return redirect('/');
        }     
    }


    public function viewcustomer($id){

    	$user = User::where('id','=', $id)->first();
    	if (Auth::check() && Auth::user()->role == 'admin' && $user) {
    		$loan = $user->loan()->orderby('updated_at','desc')->first();
    		$historys = $user->history()->where('approved','=','yes')->orderby('updated_at','desc')->take(5000)->get();
    		$rejected = $user->history()->where('approved','=','no')->orderby('updated_at','desc')->take(5000)->get();
    		$latest_loan = $user->loan()->latest()->first();
            $now = Carbon::now();

    		if ($user->role == 'customer') {
    		 	
    		 	return view('admin.viewcustomer')->with(['user' => $user, 'loan' => $loan, 'historys' =>$historys, 'rejected' => $rejected, 'latest_loan' => $latest_loan, 'now' => $now]);
    		 }else {
    		 	return 'You can only view Customer page';
    		 }
    		
    	}else {
    		
    		return redirect('/');
    	}
    }

    public function viewcollateral($id){
    	$user = User::where('id','=', $id)->first();
    	if (Auth::check() && Auth::user()->role == 'admin' && $user) {

    		return view('admin.viewcollateral')->with(['user' => $user]);


    	}else {
    		
    		return redirect('/');
    	}

    }

    public function editcustomer(Request $request, $id){
    	$user = User::where('id','=', $id)->first();

    	if (Auth::check() && Auth::user()->role == 'admin' && $user) {

    		if ($request->isMethod('get')) {
    			return view('admin.editcustomer')->with(['user' => $user]);
    			
    		}elseif ($request->isMethod('put') && $request->input('number')) {

    			$this->validate($request, [

	    	'number' => 'required|numeric|unique:users',


	    	 ]);
    		$user->update([
    			'number' => $request->input('number'),
    			]);
    		$request->session()->flash('success', $request->input('name').' Mobile number updated successfully.');
        	return redirect('/admin/customer/edit/'.$id)->with(['user' => $user]);
    			
    		}elseif ($request->isMethod('put') && $request->input('password')) {
    			$this->validate($request, [

	    	'password' =>'required|min:5|max:20',

	    	 ]);
    		 $user->update([
    			'password'=> bcrypt($request->input('password')),
    			]);
    		$request->session()->flash('success', $request->input('name').' Password updated successfully.');
        	return redirect('/admin/customer/edit/'.$id)->with(['user' => $user]);
    			
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
    		$user->update([
    			'passport'=> 'images/'.$passportname,
    			]);
    		}

    		
    		if ($request->file('idcard')) {
    			$idcard = $request->file('idcard');
    		$idcardname = $user->username."-"."idcard".".".$idcard->getClientOriginalExtension();
    		$idcard->move($destination, $idcardname);
    		$user->update([
    			'idcard'=> 'images/'.$idcardname,
    			]);
    		}

    		
    		if ($request->file('kin_passport')) {
    			$kin_passport = $request->file('kin_passport');
    		$kin_passportname = $user->username."-"."kin_passport".".".$kin_passport->getClientOriginalExtension();
    		$kin_passport->move($destination, $kin_passportname);
    		$user->update([
    			'kin_passport'=> 'images/'.$kin_passportname,
    			]);
    		}

    		
    		if ($request->file('gara1_passport')) {
    			$gara1_passport = $request->file('gara1_passport');
    		$gara1_passportname = $user->username."-"."gara1_passport".".".$gara1_passport->getClientOriginalExtension();
    		$gara1_passport->move($destination, $gara1_passportname);
    		$user->update([
    			'gara1_passport'=> 'images/'.$gara1_passportname,
    			]);
    		}

    		
    		if ($request->file('gara2_passport')) {
    			$gara2_passport = $request->file('gara2_passport');
    		$gara2_passportname = $user->usernames."-"."gara2_passport".".".$gara2_passport->getClientOriginalExtension();
    		$gara2_passport->move($destination, $gara2_passportname);
    		$user->update([
    			'gara2_passport'=> 'images/'.$gara2_passportname,
    			]);
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
        	return redirect('/admin/customer/edit/'.$id)->with(['user' => $user]);
    		}


    	}else {
    		
    		return redirect('/');
    	}

    }

    public function suspend(Request $request, $id){
        if (Auth::check() && Auth::user()->role == 'admin') {
            
        
        $user = User::where('id', '=', $id)->where('suspend', '=', 'no')->where('role', '!=', 'admin')->first();

        if($user){
                $user->update(['suspend' => 'yes']);
                $request->session()->flash('success', $user->username.' Suspended successfully.');

                if ($user->role == 'cus') {
                return redirect('/admin/customer/'.$id);
            }else {

                return back();
            }
           
        }else{
                $request->session()->flash('failed', ' User does not exixt or currently Suspended.');
            
                return redirect('/admin/customer/'.$id);
            
        }
      }else {
          
            return redirect('/');
      }
    }


    public function unsuspend(Request $request, $id){
        if (Auth::check() && Auth::user()->role == 'admin') {
            
        
        $user = User::where('id', '=', $id)->where('suspend', '=', 'yes')->where('role', '!=', 'admin')->first();

        if($user){
                $user->update(['suspend' => 'no']);
                $request->session()->flash('success', $user->username.' unsuspended successfully.');

                if ($user->role == 'cus') {
                return redirect('/admin/customer/'.$id);
            }else {

                return back();
            }
            
        }else{
                $request->session()->flash('failed', 'User does not exixt or currently unuspended.');
            return redirect('/admin/customer/'.$id);
        }
      }else {
          
            return redirect('/');
      }
    }



    public function addstaff(Request $request){

    	if (Auth::check() && Auth::user()->role == 'admin') {

    		return view('admin/addstaff');

    		}else {
    		
    		return redirect('/');
    	}
    }



    public function createstaff(Request $request){

    	if (Auth::check() && Auth::user()->role == 'admin') {

    		$this->validate($request, [

    	'name' => 'required',

    	'username' =>'required|unique:users',

    	'password' =>'required|confirmed|min:5|max:20',

    		]);

    		User::create([
    			'name' => $request->input('name'),
    			'username' => $request->input('username'),
    			'password' => bcrypt($request->input('password')),
    			'role' => 'staff',
    			]);
    		$request->session()->flash('success', 'Staff Created successfully');
        	return redirect('/admin/addstaff');

    		}else {
    		
    		return redirect('/');
    	}
    }

    public function editstaff(Request $request){
    	if (Auth::check() && Auth::user()->role == 'admin') {
    	$this->validate($request, [

    	'username' =>'required',

    	'password' =>'required|min:5|max:20',

    		]);

    	$user = User::where('username', '=', $request->input('username'))->first();
    	$user->update([
    		'password' => bcrypt($request->input('password')),
    		]);

    		$request->session()->flash('success', 'Staff Password updated successfully');
        	return redirect('/admin/addstaff');

    		}else {
    		
    		return redirect('/');
    	}

    }

    public function addadmin(Request $request){

    	if (Auth::check() && Auth::user()->role == 'admin') {

    		return view('admin/addadmin');

    		}else {
    		
    		return redirect('/');
    	}
    }

    public function addadmin2(Request $request){

        if (Auth::check() && Auth::user()->role == 'admin') {

            return view('admin/addadmin2');

            }else {
            
            return redirect('/');
        }
    }

    public function createadmin(Request $request){
    	if (Auth::check() && Auth::user()->role == 'admin') {

    		$this->validate($request, [

    	'name' => 'required',

    	'username' =>'required|unique:users',

    	'password' =>'required|confirmed|min:5|max:20',

    		]);

    		User::create([
    			'name' => $request->input('name'),
    			'username' => $request->input('username'),
    			'password' => bcrypt($request->input('password')),
    			'role' => 'admin',
    			]);
    		$request->session()->flash('success', 'Admin created successfully');
        	return redirect('/admin/addadmin');
    	
    		}else {
    		
    		return redirect('/');
    	}
    }

public function createadmin2(Request $request){
        if (Auth::check() && Auth::user()->role == 'admin') {

            $this->validate($request, [

        'name' => 'required',

        'username' =>'required|unique:users',

        'password' =>'required|confirmed|min:5|max:20',

            ]);

            User::create([
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'password' => bcrypt($request->input('password')),
                'role' => 'admin2',
                ]);
            $request->session()->flash('success', 'Admin mini created successfully');
            return redirect('/admin/addadmin2');
        
            }else {
            
            return redirect('/');
        }
    }

    public function info(){
        if (Auth::check() && Auth::user()->role == 'admin') {

            $customer = new User;
            return view('admin.info')->with(['customer' => $customer]);

            }else {
            
            return redirect('/');
        }

    }

    public function fee(Request $request, $id){
        if (Auth::check() && Auth::user()->role == 'admin') {

           $loan = Loan::where('id', '=', $id)->first();

           if ($loan && $loan->due != 'paid') {
            $request->session()->flash('success', 'Loan dafault fee updated');
               $loan->update(['due' => 'paid']);
           }else{
            $request->session()->flash('failed', 'Loan dafault fee is paid already or no loan exists');
           }
           

            return redirect('admin/customer/'.$loan->user_id);


            }else {
            
            return redirect('/');
        }

    }

    public function veri_interest(Request $request, Loan $loan){

        if (Auth::check() && Auth::user()->role == 'admin') {

           if ($loan && $loan->interest_status != 'paid') {
            $request->session()->flash('success', 'Loan Interest updated');
               $loan->update(['interest_status' => 'paid']);
           }else{
            $request->session()->flash('failed', 'Loan interest is paid already or no loan exists');
           }
           

            return redirect('admin/customer/'.$loan->user_id);


            }else {
            
            return redirect('/');
        }
    }

    public function sms($to, $message){
    	$username = 'honeypays01';
    	$password = 'Empress2011';
    	$sender = 'HONEYPAYS';
    	$data = 'username='.$username.'&password='.$password.'&sender='.$sender.'&to='.$to.'&message='.$message;

    	$ch = curl_init('http://smsc.xwireless.net/API/WebSMS/Http/v1.0a/index.php?'.$data);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$response = curl_exec($ch);
    	curl_close($ch);
    	return $response;
    }

    public function naira($number){
	return "N". number_format($number, 2);

	}

	/*public function send(){
		return $this->sms('2348106813749', urlencode('This is a test'));
	}*/
}
