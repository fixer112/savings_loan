<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class CalenderController extends Controller
{
    public function index(){
    	return view('calculator');
    }

    public function calculate(Request $request){

    	$this->validate($request, [
            'amount' => 'required|numeric|min:0',
            'rate' => 'required',
          	'date' => 'required|date_format:m/d/y|after:yesterday',
        ]);

    	$year = date('Y');
        $date = $request->input('date');
        $rate = $request->input('rate');
        $amount = $request->input('amount');

        $request->session()->put('date', $date);
        $request->session()->put('rate', $rate);
        $request->session()->put('amount', $amount);

        $holidays = [$year."01-01", $year."01-15", $year."02-12", $year."02-14", $year."02-16", $year."02-19", $year."03-11", $year."03-17", $year."03-20", $year."03-31", $year."04-01", $year."04-25", $year."05-13", $year."05-16", $year."04-20", $year."05-28", $year."06-14", $year."06-17", $year."06-21", $year."07-04", $year."09-03", $year."09-10", $year."09-23", $year."10-08", $year."10-31", $year."11-04", $year."11-11", $year."11-22", $year."12-02", $year."12-21", $year."12-25", $year."12-26", $year."12-31"];
        $MyDateCarbon = Carbon::parse($date);

        $MyDateCarbon->addWeekdays($rate+1);

        for ($i = 1; $i <= $rate+1; $i++) {

    	if (in_array(Carbon::parse($date)->addWeekdays($i)->toDateString(), $holidays) || in_array(Carbon::parse($date)->toDateString(), $holidays)) {

        $MyDateCarbon->addWeekdays();

    }
}

		if ($rate == "30") {

			$accured_interest = (1/100)*$amount*$rate;
			
		}elseif ($rate == "90") {
			$accured_interest = (2/100)*$amount*$rate;

		}elseif ($rate == "180") {
			$accured_interest = (3/100)*$amount*$rate;

		}elseif ($rate == "360") {
			$accured_interest = (4/100)*$amount*$rate;
		}
        $gain = $amount + $accured_interest;
		$interest = (20/100)*$gain;
		$total_earning = $gain - $interest;
		$request->session()->flash('return_date', $MyDateCarbon->format('l jS F Y'));
		$request->session()->flash('earning', $total_earning);
        return redirect('/calculator');

    }
}
