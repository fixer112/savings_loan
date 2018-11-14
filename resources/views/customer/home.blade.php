@extends('layouts.app')
@section('css')
<link href="{{ asset('css/cus_style.css') }}" rel="stylesheet">
<style type="text/css">
        .img img {
            width: 30%;
        }
        .img{
            padding-bottom: 15px;
        }
        .img2 img {
            width: 38%;
        }
    </style>
@endsection

@section('title')
HONEYPAYS | {{Auth::user()->username}}
@endsection

@section('menu')

@endsection

@section('content')
@php
$id = Auth::user()->id;
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><strong>Dashboard</strong></div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('failed'))
                        <div class="alert alert-danger">
                            {{ session('failed') }}
                        </div>
                    @endif

                    <div class="row">
                    <div class="col-md">

                        
                            Savings Account Balance : <strong>@money(Auth::user()->savings_balance)</strong>
                            <p>Loan Balance : <strong>@money(Auth::user()->loan_balance) </strong></p><hr>

                            <div class="img"><img src="/public/{{Auth::user()->passport}}"></div>


                            <p>Name: {{ Auth::user()->name }}</p>

                            <p>Account Number: {{ Auth::user()->username }}</p>
                        
                            <p>Residetial Address: {{ Auth::user()->resi_add }} </p>

                            <p>Business Address: {{ Auth::user()->busi_add }} </p>

                            <p>Nature of business: {{ Auth::user()->nature_add }} </p>

                            <p>Phone Number: {{ Auth::user()->number }} </p>

                            @if (isset($loan) && $loan->veri_remark !='pending')


                            <p>Loan Application Date: {{ $loan->created_at->format('d/m/Y') }} </p>

                            <p>Loan Due Date: {{ $loan->due_date->format('d/m/Y') }} </p>

                            @php
                            $week_due_date = $latest_loan->week_due_date;
                            $due_date = $latest_loan->due_date;
                            $skip_due = $latest_loan->skip_due;

                            @endphp

                            <p>Week Due Count: <strong>{{$week_due_date->diffInWeeks($due_date, false) >= 0 ? $week_due_date->diffInWeeks($now, false) + $skip_due : '0'}}</strong></p>

                            <p>Interest Status: <strong style="color: {{ $latest_loan->Interest_status =='paid' ? 'green' : 'red' }}" >{{ $loan->veri_remark }} </strong></p>

                            <p>Loan Category: @money($loan->loan_category) </p>

                            <p >Verification Remark: <strong style="color: {{ $loan->veri_remark =='Approved' ? 'green' : 'red' }}" >{{ $loan->veri_remark }} </strong></p>
                            @else
                            <p><strong style="color:red"> No loan history yet</strong></p>
                            @endif
                        
                    </div>
                    <div class="col-md">
                    @if (!isset($loanveri) && !isset($transveri))

                    <div class="withdraw"> 
                    <form class="form-horizontal" method="POST" action="/customer/withdraw">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label"><strong>Request Withdrwal</strong></label>

                            <div class="col-md-6">
                                <input id="Amount" type="number" class="form-control" name="amount" value="{{ old('amount') }}" required autofocus>

                                @if ($errors->has('account'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('account') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                         <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Withdraw
                                </button>
                            </div>
                        </div>
                    </form>
                    </div> 
                    
                    @else
                    <div class="error alert alert-danger">
                    Transaction avaiting approval Or loan not approved yet(not paid)....
                    </div>
                    @endif

                    <div class="password">
                    <strong>Change Your Password</strong>
                    
                        <form class="form-horizontal" method="POST" action="/customer/changepass">
                        {{ csrf_field() }}
                        {{method_field('PUT')}}

                        <div class="form-group{{ $errors->has('old_password') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="old_password" placeholder="Old Password" required>

                                @if ($errors->has('old_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('old_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                <input id="password_confirmation" type="password" class="form-control" name="new_password" placeholder="New Password" required>

                                @if ($errors->has('new_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                         <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>
                    </div>
                    </div>
        <div class="card-header history">
        TRANSACTION HISTORY ({{$historys->count()}})
        </div>
        @if ($historys->count()>0)
          <div class="table-responsive">
            <table class="display table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                <th>Transaction ID</th>
                  <th>Transaction Type</th>
                  <th>Recieved by</th>
                  <th>Transaction description</th>
                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Applied Date</th>
                  <th>Verified Date</th>
                  

                </tr>
              </thead>
             
              <tbody>
              @foreach ($historys as $history)

                <tr>
                <td> {{$history->id}} </td>
                  <td> {{$history->type}} </td>
                  <td> {{$history->recieved_by}} </td>
                  <td> {{$history->description}} </td>
                  <td> @money($history->debit) </td>
                  <td> @money($history->credit) </td>
                  <td> {{$history->created_at->format('d/m/Y H:i:s')}} </td>
                  <td> {{$history->updated_at->format('d/m/Y H:i:s')}} </td>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO Transaction Found
        </div>
        @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
