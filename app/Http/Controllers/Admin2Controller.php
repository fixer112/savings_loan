<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\History;
use App\User;
use App\Loan;
use App\Verify;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Log;

class Admin2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin2');
    }

    public function index(Request $request){
    	
    		$id = Auth::user()->mentor;
            $referal = User::where('referal',$id)->pluck('id');

    		$approved = History::where('approved','=','yes')->whereIn('user_id',$referal)->orderby('updated_at','desc')->paginate(500);

    		$pendings = History::where('approved','=','pending')->where('type', '!=','withdraw')->whereIn('user_id',$referal)->orderby('updated_at','desc')->paginate(500);
            
    		$rejected = History::where('approved','=','no')->whereIn('user_id',$referal)->orderby('updated_at','desc')->paginate(500);

             $loans = new Loan;

             $dues = Verify::where('status', 'approved')->whereIn('user_id',$referal)->where('active','0')->paginate(500);


    		$customer = new User;
             $now = Carbon::now();
    		
    		return view('admin2.home')->with(['approved' => $approved,'customer' => $customer, 'pendings' => $pendings, 'rejected' => $rejected, 'loans'=>$loans, 'dues'=>$dues, 'now'=>$now]);
    	
    }

    public function newcus(){
    	
    	return view('admin2.reg');
    	
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
            'referal' => $request->input('mentor'),
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
        return redirect('admin2/newcustomer');

    
    	


    }

    public function newtran(){
    	
    		return view('admin2.transaction');

    	


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

    			$user = User::where('username','=', $request->input('username'))->first();
                if ($user->referal != Auth::user()->mentor) {
                    $request->session()->flash('failed', $request->input('username').' is not branch '.Auth::user()->mentor.' customer');
                    return redirect('admin2/transaction');
                }
    			$loan = $user->loan()->where('veri_remark','=','Not Approved')->orderby('updated_at','desc')->first();
    			$pending = $user->history()->where('approved','=','pending')->orderby('updated_at','desc')->first();
    			$latest_loan = $user->loan()->latest()->first();
                $now = Carbon::now();
                $subject = 'Transaction Alert';
                $username = $user->username;

    			
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

                         Log::info($this->app($subject,$message,$username));
			    		$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));
			    		
    					return redirect('admin2/transaction');

			    			}else {
			    			$request->session()->flash('failed', $request->input('username').' has Insufficient Account Balance or has a pending transaction or has an unpaid loan or amount is less than 0');
			    			
			    			return redirect('admin2/transaction');
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
    					if ($latest_loan->veri_remark == 'Not Approved') {
    						$latest_loan->update(['veri_remark' => 'Approved']);
    					}


    					$amount = $this->naira($request->input('amount'));
				    	$savings_balance = $this->naira($user->savings_balance);
				    	$loan_balance = $this->naira($user->loan_balance);
				    	$loan_amount = $this->naira($request->input('category'));

    					//Alert User by SMS
    					$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: '.$user->username.' Your loan have been fully paid. Transaction Type: '.$request->input('description').' Transaction Amt: '.$amount.' Avail Savings Bal: '.$savings_balance.' Loan Bal: '.$loan_balance;

                         Log::info($this->app($subject,$message,$username));
			    		$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));

    				}elseif ($user->loan_balance > $request->input('amount')) {
    					$balance = $user->loan_balance - $request->input('amount');
    					$user->update(['loan_balance' => $balance]);

    					$amount = $this->naira($request->input('amount'));
				    	$savings_balance = $this->naira($user->savings_balance);
				    	$loan_balance = $this->naira($user->loan_balance);
				    	$loan_amount = $this->naira($request->input('category'));

    					$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: '.$user->username.' Loan partly paid. Transaction Type: '.$request->input('description').' Transaction Amt: '.$amount.' Avail Savings Bal: '.$savings_balance.' Loan Bal: '.$loan_balance;

                         Log::info($this->app($subject,$message,$username));
    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


    				}elseif($user->loan_balance == 0) {
    					$balance =$user->savings_balance + $request->input('amount');
    					$user->update(['savings_balance' => $balance]);

    					$amount = $this->naira($request->input('amount'));
				    	$savings_balance = $this->naira($user->savings_balance);
				    	$loan_balance = $this->naira($user->loan_balance);
				    	$loan_amount = $this->naira($request->input('category'));

    					$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: '.$user->username.' Transaction Type: '.$request->input('description').' Transaction Amt: '.$amount.' Avail Savings Bal: '.$savings_balance.' Loan Bal: '.$loan_balance;

                         Log::info($this->app($subject,$message,$username));
    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));
    				}

    				//}
                    if ($user->open_fee=='1') {
                           $balance = $user->savings_balance - 2000;
                           $user->update([
                           'savings_balance' => $balance,
                           'open_fee' => '0',
                            ]);

                           History::create([
                        'recieved_by' => 'HoneyPays Mcredit',
                        'description' => 'Account Opening Fee',
                        'debit' => '2000',
                        'credit' => '0',
                        'type' => 'withdraw',
                        'approved' => 'yes',
                        'user_id' => $user->id,
                    ]);
                        $savings_balance = $this->naira($user->savings_balance);

                        /*$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: '.$user->username.' Amount of N2000.00 has been deducted from your savings balance has account open fee, your new balance is '.$savings_balance;*/

                       $message = 'NOTIFICATION ' .Carbon::now(). ' Acct: ' . $user->username . ' Loan Application Approved Transaction Type: Account Opening Fee Transaction Amt: ₦2000 Avail Savings Bal: ' . $savings_balance .' HoneyPays | TrulyPays';

                         Log::info($this->app($subject,$message,$username));
                         Log::info($this->sms($to, urlencode($message)));
                        }
			        $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));
			        //return $pending."</br>".$loan;
    					return redirect('admin2/transaction');
    					}else {
    						$request->session()->flash('failed', $request->input('username').'  has a pending transaction awaiting approval');
			    			// return $pending."</br>".$loan;
			    			return redirect('admin2/transaction');
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

    					$message = 'NOTIFICATION ' .Carbon::now(). ' Acct: ' . $user->username . ' Loan Application Approved Transaction Type: ' . $request->input('description') . ' Transaction Amt: ' . $loan_amount . ' Avail Savings Bal: ' . $savings_balance . ' Loan Bal: ' . $loan_balance . ' HoneyPays | TrulyPays';

                         Log::info($this->app($subject,$message,$username));
    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


			        $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));
    					return redirect('admin2/transaction');

    				}else {
    					$request->session()->flash('failed', $request->input('username').' has unpaid interest fee or default fee');
			    		return redirect('admin2/transaction');
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

                         Log::info($this->app($subject,$message,$username));
                        $request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


                    $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));
                        return redirect('admin2/transaction');
                }
    					
    				}else {
    					$request->session()->flash('failed', $request->input('username').' has a pending transaction or has an unpaid loan');
			    		return redirect('admin2/transaction');
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

                         Log::info($this->app($subject,$message,$username));
    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


			        $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));
    					return redirect('admin2/transaction');
                    
    					
    				}else {
    					$request->session()->flash('failed', $request->input('username').' has Insufficient fund.');
			    		return redirect('admin2/transaction');
    				}

                    }else {
                        $request->session()->flash('failed', $request->input('username').' has no loan history');
                        return redirect('admin2/transaction');
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

                         Log::info($this->app($subject,$message,$username));
    					$request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));


			        $request->session()->flash('success', $request->input('type').' successfully applied for '.$request->input('username'));
    					return redirect('admin2/transaction');


    				}else {
    				$request->session()->flash('failed', $request->input('username').' has Insufficient fund or interest already paid');
			    	return redirect('admin2/transaction');
    				}

    				}else {
                        $request->session()->flash('failed', $request->input('username').' has no loan history');
                        return redirect('admin2/transaction');
                    }
    			}

    		}else{
    			$request->session()->flash('failed', $request->input('username').' is not a customer');
			    return redirect('admin2/transaction');
    		}

    		
    }

    	public function changepass(Request $request){
    		

    			if (!$request->isMethod('put')) {
    				return view('admin2.changepass');
    				
    			}else {
    				$this->validate($request, [
            'old_password' =>'required',
            'new_password' =>'required|min:5|max:20',
          
        ]);
    		if (Hash::check($request->input('old_password'), Auth::user()->password)) {
    			
    			Auth::user()->update(['password' => bcrypt($request->input('new_password'))]);
    			$request->session()->flash('success', 'Password Changed successfully');
    			return redirect('/admin2/changepass');
    		}else{
    			$request->session()->flash('failed', 'Old Password is invalid');
    			return redirect('/admin2/changepass');

    		}
    			}

    	}
    

    public function search(Request $request){

    //$keyword = $request->input('search');

    $searchs = $searchs = User::where('role', '=', 'customer')->where('referal','=', Auth::user()->mentor)->paginate(500);

    /*User::where(function ($query) use ($keyword) {
            $query->where('role', '=', 'customer')->where('suspend', '=', 'no');
        })->where(function ($query) use ($keyword) {
        $query->where('username', 'LIKE', '%'.$keyword.'%')
        //->orWhere('email', 'LIKE', '%'.$keyword.'%')
        ->orWhere('number', 'LIKE', '%'.$keyword.'%');

        })->get();*/
    //$request->session()->put('search', $keyword);
    
    return view('admin2.search', compact('searchs'));
    }


    public function viewcustomer($id){

    	$user = User::where('id','=', $id)->where('suspend', '=', 'no')->first();
        if ($user->referal != Auth::user()->mentor) {
                    $request->session()->flash('failed', $request->input('username').' is not branch '.Auth::user()->mentor.' customer');
                    return redirect('admin2');
                }
    	if (Auth::check() && Auth::user()->role == 'admin2' && $user) {
    		$loan = $user->loan()->orderby('updated_at','desc')->first();
    		$historys = $user->history()->where('approved','=','yes')->orderby('updated_at','desc')->paginate(500);
    		$rejected = $user->history()->where('approved','=','no')->orderby('updated_at','desc')->paginate(500);
            $latest_loan = $user->loan()->latest()->first();
            $now = Carbon::now();

    		if ($user && $user->role == 'customer') {
    		 	
    		 	return view('admin2.viewcustomer')->with(['user' => $user, 'loan' => $loan, 'historys' =>$historys, 'rejected' => $rejected, 'latest_loan' => $latest_loan, 'now' => $now]);
    		 }else {
    		 	return 'You can only view Customer page or customer suspended';
    		 }
    		
    	}
    }

    public function viewcollateral($id){
    	$user = User::where('id','=', $id)->where('suspend', '=', 'no')->first();
        if ($user->referal != Auth::user()->mentor) {
                    $request->session()->flash('failed', $request->input('username').' is not branch '.Auth::user()->mentor.' customer');
                    return redirect('admin2');
                }
    	if (Auth::check() && Auth::user()->role == 'admin2' && $user) {

    		return view('admin2.viewcollateral')->with(['user' => $user]);
        }
    }

    public function editcustomer(Request $request, $id){
    	$user = User::where('id','=', $id)->where('suspend', '=', 'no')->first();
        if ($user->referal != Auth::user()->mentor) {
                    $request->session()->flash('failed', $request->input('username').' is not branch '.Auth::user()->mentor.' customer');
                    return redirect('admin2');
                }

    	if (Auth::check() && Auth::user()->role == 'admin2' && $user) {

    		if ($request->isMethod('get')) {
    			return view('admin2.editcustomer')->with(['user' => $user]);
    			
    		}elseif ($request->isMethod('put') && $request->input('number')) {

    			$this->validate($request, [

	    	'number' => 'required|numeric|unique:users',


	    	 ]);
    		$user->update([
    			'number' => $request->input('number'),
    			]);
    		$request->session()->flash('success', $request->input('name').' Mobile number updated successfully.');
        	return redirect('/admin2/customer/edit/'.$id)->with(['user' => $user]);
    			
    		}elseif ($request->isMethod('put') && $request->input('password')) {
    			$this->validate($request, [

	    	'password' =>'required|min:5|max:20',

	    	 ]);
    		 $user->update([
    			'password'=> bcrypt($request->input('password')),
    			]);
    		$request->session()->flash('success', $request->input('name').' Password updated successfully.');
        	return redirect('/admin2/customer/edit/'.$id)->with(['user' => $user]);
    			
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
        	return redirect('/admin2/customer/edit/'.$id)->with(['user' => $user]);
    		}

        }
    }


    
}
