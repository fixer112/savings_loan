<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Verify;
use App\User;
use Carbon\Carbon;

class VerifyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin2')->only('verifyadd');
        $this->middleware('admin')->only('reject','approve','activate');
    }

    public function verifyadd(Request $request){


    	if (!$request->isMethod('post')) {

    		return view('verify.add');
    	}else {

    		$user = User::where('username', $request->accno)->first();
    		
    		$validate = $this->validate($request, [
	        'accno' => 'required|numeric|exists:users,username',
	        'loan' => 'required',
	        'form1' => 'required|image|max:200',
	        'form2' => 'required|image|max:200',
	        'form3' => 'required|image|max:200',
	        ]);

	        $destination = public_path('/verify');

	        $form1 = $request->file('form1');
    		$form1name = "form1-".$this->randomstring(4).time().$user->id.".".$form1->getClientOriginalExtension();

    		$form2 = $request->file('form2');
    		$form2name = "form2-".$this->randomstring(4).time().$user->id.".".$form2->getClientOriginalExtension();

    		$form3 = $request->file('form3');
    		$form3name = "form3-".$this->randomstring(4).time().$user->id.".".$form3->getClientOriginalExtension();

    		$form1->move($destination, $form1name);
    		$form2->move($destination, $form2name);
    		$form3->move($destination, $form3name);

    		Verify::create([
    			'loan' => $request->loan,
    			'staff_id' => Auth::user()->username,
    			'user_id' => $user->id,
    			'form1' => 'verify/'.$form1name,
    			'form2' => 'verify/'.$form2name,
    			'form3' => 'verify/'.$form3name,

    		]);
    		$request->session()->flash('success', 'Verification added successfully');
    		
    		
    		return back();
    	}

        

    }

    public function verifyview(Request $request){

    	$pendings = Verify::where('status', 'pending')->get();
    	$approved = Verify::where('status', 'approved')->get();
    	$rejected = Verify::where('status', 'rejected')->get();
    	$customer = new User;

    	return view('verify.view', compact('pendings', 'approved', 'rejected', 'customer'));
    }

    public function reject(Request $request, Verify $verify){

    	if ($verify && $verify->status != 'pending') {

    		$validate = $this->validate($request, [
    			'reason'=>'required',
	        ]);

    		$verify->update(['status' => 'rejected', 'reason' => $request->reason]);

    		$user = $verify->user()->first();

    		$to = $user->number;
    		$reason = $request->reason;

        $message = 'We are sorry to inform you that your application for loan was rejected. Reason: '.$reason;

        $request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));

    		$request->session()->flash('success', 'Verification rejected successfully');
    	}else {
    		$request->session()->flash('failed', 'Verification does not exist not pending');
    	}

    	return back();
    }

    public function approve(Request $request, Verify $verify){

    	if ($verify && $verify->status =='pending' ) {
    		
    		$validate = $this->validate($request, [
    			'due_date'=>'required',
	        ]);
    		$due = Carbon::createFromFormat('Y-m-d', $request->due_date);

    		$verify->update(['status' => 'approved', 'due_date' => $due]);

    		$user = $verify->user()->first();

    		$to = $user->number;
    		

        $message = 'We are happy to inform you that your application for loan was approved. Loan will be granted by: '.$due->format('l jS F Y');

        $request->session()->flash('sms', 'Message Response: ' . $this->sms($to, urlencode($message)));

    		$request->session()->flash('success', 'Verification approved successfully');
    	}else {
    		$request->session()->flash('failed', 'Verification does not exist not pending');
    	}

    	return back();
    	}

        public function activate(Request $request, Verify $verify){
            if ($verify->active == '0') {
            $verify->update(['active' => '1']);
            $request->session()->flash('success', 'Due activated');
                
            }
            return back();
        }

}
