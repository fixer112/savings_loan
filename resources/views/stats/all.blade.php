@extends('layouts.app')
@php

function naira($number){
    return "₦". number_format($number, 2);

    }


if ($branch == "") {
  $branch = Auth::user()->mentor;
}
$users = $data['branch']->where('referal', $branch)->pluck('id')->all();

/*$start = $data['carbon']->now()->startOfMonth()->format('Y-m-d');
$end = $data['carbon']->now()->endOfMonth()->format('Y-m-d');*/

$start = $from;
$end = $to;

$total_approved = $data['His']->whereIn('user_id', $users)->where('approved', 'yes')->whereBetween('updated_at', [$start, $end])->get();

$total_loan = $data['His']->whereIn('user_id', $users)->where('approved', 'yes')->whereBetween('updated_at', [$start, $end])->where('type', 'loan')->get();
$total_loan_sum = $total_loan->sum('debit');

$total_withdraw = $data['His']->whereIn('user_id', $users)->where('approved', 'yes')->whereBetween('updated_at', [$start, $end])->where('type', 'withdraw')->get();
$total_withdraw_sum = $total_withdraw->sum('debit');

$total_deposit = $data['His']->whereIn('user_id', $users)->where('approved', 'yes')->whereBetween('updated_at', [$start, $end])->where('type', 'deposit')->get();
$total_deposit_sum = $total_deposit->sum('credit');

$total_interest = $data['His']->whereIn('user_id', $users)->where('approved', 'yes')->whereBetween('updated_at', [$start, $end])->where('type', 'interest_fee')->get();
$total_interest_sum = $total_interest->sum('debit');

$total_default = $data['His']->whereIn('user_id', $users)->where('approved', 'yes')->whereBetween('updated_at', [$start, $end])->where('type', 'default_fee')->get();
$total_default_sum = $total_default->sum('debit');

$total_approved_sum = $total_deposit_sum + $total_withdraw_sum + $total_loan_sum + $total_default_sum + $total_interest_sum;
@endphp

@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
<link href="{{ asset('fonts/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
@endsection

@section('title')
HONEYPAYS | All Time Statistics
@endsection

@section('script')

@endsection

@section('menu')

@endsection

@section('content')
<div class="container">
<script type="text/javascript">
  function go(arr){
    event.preventDefault();
    document.getElementById('details').value = JSON.stringify(arr);
    document.getElementById('submit').submit();
  }
</script>
  <form id="submit" action="/stats/records" method="POST" style="display: none;">
    <input type="text" name="details" value="" id="details">
      @csrf
  </form>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            
                <div class="card-header"><strong>All Time Statistics</strong></div>

            <div class="card-body">
            @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    @if (session('sms'))
                        <div class="alert alert-success">
                            {{ session('sms') }}
                        </div>
                    @endif

                    @if (session('failed'))
                        <div class="alert alert-danger">
                            {{ session('failed') }}
                        </div>
                    @endif

                
  {{-- <h2>Cards Columns</h2>
  <p>The .card-columns class creates a masonry-like grid of cards. The layout will automatically adjust as you insert more cards.</p>
  <p><strong>Note:</strong> The cards are displayed vertically on small screens (less than 576px):</p> --}}
  <div>
  <form action="/stats/all" method="GET" accept-charse="utf-8" class="form-inline">

    @if(Auth::user()->role == 'admin')
    <div class="input-group mb-2 mr-2">
      <div class="input-group-prepend">
        <div class="input-group-text">Branch</div>
      </div>
   <select id="branch" type="text" class="form-control" name="branch" required autofocus>

    <option value="" {{ $branch== "" ? "selected": ""}}>Choose</option>

    @foreach($data['nobs'] as $nob)

    <option value="{{$nob}}" {{$branch== $nob ? "selected": ""}}>{{$nob}}</option>

    @endforeach
                                    
    </select>
    @if ($errors->has('branch'))
      <span class="help-block">
          <strong>{{ $errors->first('branch') }}</strong>
      </span>
    @endif
  </div>
  @endif

    <div class="input-group mb-2 mr-2">
      <div class="input-group-prepend">
        <div class="input-group-text">From</div>
      </div>
    <input id="from" class="form-control" type="date" name="from" value="{{session('from')}}">
    @if ($errors->has('from'))
      <span class="help-block">
          <strong>{{ $errors->first('from') }}</strong>
      </span>
  @endif
    </div>

    <div class="input-group mb-2 mr-2">
      <div class="input-group-prepend">
        <div class="input-group-text">To</div>
      </div>
    <input id="to" class="form-control" type="date" name="to" value="{{session('to')}}">
    @if ($errors->has('to'))
        <span class="help-block">
            <strong>{{ $errors->first('to') }}</strong>
        </span>
    @endif
    </div>
    <button type="submit" class="btn btn-primary mb-2">Submit</button>
  </form>  
  </div>
  <h2><strong>Total Transaction</strong></h2>
  <div class="row">
    <div class="card pad text-white bg-primary col-md-5">
    <a href="" onclick="go({{$total_approved}})">
      <div class="card-body text-center">
        <h4 class="card-title">Number Of Transactions</h4>
        <p><strong>{{count($total_approved)}}</strong></p>
      </div>
      </a>
    </div>
    
    <div class="card pad text-white bg-success col-md-5">
      <div class="card-body text-center">
       <h4 class="card-title">Transaction Sum</h4>
        <p><strong>{{naira($total_approved_sum)}}</strong></p>
       </div>
      </div>
    </div>
  
  
  <div style="margin-top: 50px"><h2><strong>Total Deposit</strong></h2></div>

    <div class="row">

   
      <div class="card pad text-white bg-primary col-md-5">
         <a href="" onclick="go({{$total_deposit}})">
        <div class="card-body text-center">
          <h4 class="card-title">Number Of Deposits </h4>
          <p><strong>{{count($total_deposit)}}</strong></p>
        </div>
          </a>
      </div>
      

      
      <div class="card pad text-white bg-success col-md-5">
        <div class="card-body text-center">
         <h4 class="card-title">Deposit Sum</h4>
          <p><strong>{{naira($total_deposit_sum)}}</strong></p>
         </div>
        </div>
    </div>

    <div style="margin-top: 50px"><h2><strong>Total Withdraws</strong></h2></div>
    <div class="row">
      <div class="card pad text-white bg-primary col-md-5">
      <a href="" onclick="go({{$total_withdraw}})">
        <div class="card-body text-center">
          <h4 class="card-title">Number Of Withdraws </h4>
          <p><strong>{{count($total_withdraw)}}</strong></p>
        </div>
        </a>
      </div>
      
      <div class="card pad text-white bg-success col-md-5">
        <div class="card-body text-center">
         <h4 class="card-title">Withdraw Sum</h4>
          <p><strong>{{naira($total_withdraw_sum)}}</strong></p>
         </div>
        </div>
    </div>


    <div style="margin-top: 50px"><h2><strong>Total Loans</strong></h2></div>
    <div class="row">
      <div class="card pad text-white bg-primary col-md-5">
        <a href="" onclick="go({{$total_loan}})">
        <div class="card-body text-center">
          <h4 class="card-title">Number Of Loans </h4>
          <p><strong>{{count($total_loan)}}</strong></p>
        </div>
        </a>
      </div>
      
      <div class="card pad text-white bg-success col-md-5">
        <div class="card-body text-center">
         <h4 class="card-title">Loan Sum</h4>
          <p><strong>{{naira($total_loan_sum)}}</strong></p>
         </div>
        </div>
    </div>

    <div style="margin-top: 50px"><h2><strong>Total Interest</strong></h2></div>
    <div class="row">
      <div class="card pad text-white bg-primary col-md-5">
      <a href="" onclick="go({{$total_interest}})">
        <div class="card-body text-center">
          <h4 class="card-title">Number Of Interests </h4>
          <p><strong>{{count($total_interest)}}</strong></p>
        </div>
        </a>
      </div>
      
      <div class="card pad text-white bg-success col-md-5">
        <div class="card-body text-center">
         <h4 class="card-title">Interest Sum</h4>
          <p><strong>{{naira($total_interest_sum)}}</strong></p>
         </div>
        </div>
    </div>


    <div style="margin-top: 50px"><h2><strong>Total Default Fee</strong></h2></div>
    <div class="row">
      <div class="card pad text-white bg-primary col-md-5">
      <a href="" onclick="go({{$total_default}})">
        <div class="card-body text-center">
          <h4 class="card-title">Number Of Default Fee </h4>
          <p><strong>{{count($total_default)}}</strong></p>
        </div>
        </a>
      </div>
      
      <div class="card pad text-white bg-success col-md-5">
        <div class="card-body text-center">
         <h4 class="card-title">Default Fee Sum</h4>
          <p><strong>{{naira($total_default_sum)}}</strong></p>
         </div>
        </div>
    </div>
                   </div>
          </div>
        </div>
    </div>
</div>

@endsection