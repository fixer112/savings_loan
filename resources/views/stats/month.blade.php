@extends('layouts.app')
@php

function naira($number){
    return "₦". number_format($number, 2);

    }

$today = $data['carbon']->now()->format('Y-m-d');

$users = $data['branch']->where('referal', Auth::user()->mentor)->pluck('id')->all();

$start = $data['carbon']->now()->startOfMonth()->format('Y-m-d');
$end = $data['carbon']->now()->endOfMonth()->format('Y-m-d');

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
HONEYPAYS | This Month Statistics
@endsection

@section('script')

@endsection

@section('menu')

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            
                <div class="card-header"><strong>This Month Statistics</strong></div>

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
  <h2><strong>Total Transaction</strong></h2>
  <div class="row">
    <div class="card pad text-white bg-primary col-md-5">
      <div class="card-body text-center">
        <h4 class="card-title">Number Of Transactions</h4>
        <p><strong>{{count($total_approved)}}</p></strong>
      </div>
    </div>
    
    <div class="card pad text-white bg-success col-md-5">
      <div class="card-body text-center">
       <h4 class="card-title">Transaction Sum</h4>
        <p><strong>{{naira($total_approved_sum)}}</p></strong>
       </div>
      </div>
    </div>
  
  <div style="margin-top: 50px"><h2><strong>Total Deposit</strong></h2></div>
    <div class="row">
      <div class="card pad text-white bg-primary col-md-5">
        <div class="card-body text-center">
          <h4 class="card-title">Number Of Deposits </h4>
          <p><strong>{{count($total_deposit)}}</p></strong>
        </div>
      </div>
      
      <div class="card pad text-white bg-success col-md-5">
        <div class="card-body text-center">
         <h4 class="card-title">Deposit Sum</h4>
          <p><strong>{{naira($total_deposit_sum)}}</p></strong>
         </div>
        </div>
    </div>

    <div style="margin-top: 50px"><h2><strong>Total Withdraws</strong></h2></div>
    <div class="row">
      <div class="card pad text-white bg-primary col-md-5">
        <div class="card-body text-center">
          <h4 class="card-title">Number Of Withdraws </h4>
          <p><strong>{{count($total_withdraw)}}</p></strong>
        </div>
      </div>
      
      <div class="card pad text-white bg-success col-md-5">
        <div class="card-body text-center">
         <h4 class="card-title">Withdraw Sum</h4>
          <p><strong>{{naira($total_withdraw_sum)}}</p></strong>
         </div>
        </div>
    </div>


    <div style="margin-top: 50px"><h2><strong>Total Loans</strong></h2></div>
    <div class="row">
      <div class="card pad text-white bg-primary col-md-5">
        <div class="card-body text-center">
          <h4 class="card-title">Number Of Loans </h4>
          <p><strong>{{count($total_loan)}}</p></strong>
        </div>
      </div>
      
      <div class="card pad text-white bg-success col-md-5">
        <div class="card-body text-center">
         <h4 class="card-title">Loan Sum</h4>
          <p><strong>{{naira($total_loan_sum)}}</p></strong>
         </div>
        </div>
    </div>

    <div style="margin-top: 50px"><h2><strong>Total Interest</strong></h2></div>
    <div class="row">
      <div class="card pad text-white bg-primary col-md-5">
        <div class="card-body text-center">
          <h4 class="card-title">Number Of Interests </h4>
          <p><strong>{{count($total_interest)}}</p></strong>
        </div>
      </div>
      
      <div class="card pad text-white bg-success col-md-5">
        <div class="card-body text-center">
         <h4 class="card-title">Interest Sum</h4>
          <p><strong>{{naira($total_interest_sum)}}</p></strong>
         </div>
        </div>
    </div>


    <div style="margin-top: 50px"><h2><strong>Total Default Fee</strong></h2></div>
    <div class="row">
      <div class="card pad text-white bg-primary col-md-5">
        <div class="card-body text-center">
          <h4 class="card-title">Number Of Default Fee </h4>
          <p><strong>{{count($total_default)}}</p></strong>
        </div>
      </div>
      
      <div class="card pad text-white bg-success col-md-5">
        <div class="card-body text-center">
         <h4 class="card-title">Default Fee Sum</h4>
          <p><strong>{{naira($total_default_sum)}}</p></strong>
         </div>
        </div>
    </div>





                   </div>
          </div>
        </div>
    </div>
</div>

@endsection