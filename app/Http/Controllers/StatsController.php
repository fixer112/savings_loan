<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class StatsController extends Controller
{
    public function all(Request $request){
	    	if ($request->branch) {
	        $validate = $this->validate($request, [
	        'branch' => 'required|numeric|exists:users,mentor',
	        ]);
	        $from = $request->from;
	        $branch = $request->branch; 
	    }else {
	        $branch = "";
	    }
	    if (!empty($request->from) && !empty($request->to)) {

	    	$from = Carbon::createFromFormat('Y-m-d', $request->from)->startOfDay()->format('Y-m-d H:i:s');
	        $to = Carbon::createFromFormat('Y-m-d', $request->to)->endOfDay()->format('Y-m-d H:i:s');
	        $min = Carbon::createFromFormat('Y-m-d', $request->from)->subDays(1)->format('Y-m-d');
	        session(['from'=>$request->from, 'to'=>$request->to]);

	        $validate = $this->validate($request, [
	        'from' => 'required|date_format:Y-m-d',
	        'to' => 'required|date_format:Y-m-d|after:'.$min,
	        ]);
	    }else {
	        $from = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
	        $to = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
	        session(['from'=>Carbon::now()->format('Y-m-d'), 'to'=>Carbon::now()->format('Y-m-d')]);
	    }

	    
	    return view('stats.all', compact('from','to','branch'));
    }
}
