@extends('layouts.app')
@section('css')
<link href="{{ asset('css/cus_style.css') }}" rel="stylesheet">
@endsection

@section('js')

@endsection

@section('title')
HONEYPAY | INVESTMENT CALCULATOR
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><strong>INVESTMENT CALCULATOR</strong></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                    <div class="col-md">
                    
                    <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="/calculator">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label for="amount" class="col-md-4 control-label">Amount</label>

                            <div class="col-md-10">
                                <input id="amount" type="number" class="form-control" name="amount" value="{{ session('amount') }}" required autofocus>

                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
                            <label for="rate" class="col-md-4 control-label">Tenure/Rate</label>

                            <div class="col-md-10">
                                <select id="rate" type="option" class="form-control" name="rate" value="{{ session('rate') }}" required>
                                <option value="30">30Days/1%daily</option>
                                <option value="90">90Days/2%daily</option>
                                <option value="180">180Days/3%daily</option>
                                <option value="360">360Days/4%daily</option>
                                </select>

                                @if ($errors->has('rate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('rate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                            <label for="date" class="col-md-4 control-label">Investment date</label>

                            <div class="col-md-10">
                                <input id="date" type="text" class="form-control" name="date" value="{{ session('date') }}" placeholder="mm/dd/yy e.g 03/21/18" required>

                                @if ($errors->has('date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    CALCULATE
                                </button>
                            </div>
                        </div>
                    </form>
                    @if (session('earning'))
                        
                           <p> <strong>Your Expected Return Earning is : </strong><b style="color: green">&#8358 {{ session('earning') }}</b></p>
                       
                    @endif

                     @if (session('return_date'))
                        
                            <p> <strong>Your Expected Return Date is : </strong><b style="color: green">{{ session('return_date') }}</b></p>
                       
                    @endif
                </div>
                        
                    </div>
                <div class="col-md">
                <h3 style="color: red;font-weight: 900">RULES</h3>
                <b><p>
                	<p style="color: red">Return Earning</p>
                	<p>1.) Your assured interest is your amount*rate*tenure e.g 10000*1%*30</p>
                	<p>2.) Invested amount will be added to your assured amount, in which 20% will be deducted to give total earning</p>

                	<p style="color: red">Return Date</p>
                	<p>1.) Your investment date plus the number of tenure day excluding weekends and our official holidays</p>
                	<p>2.) You can get a copy of our official holidays <a href="http://www.honeypays.com.ng/wp-content/uploads/2018/03/Calender.pdf"> here</a></p>

                </p>
                </b>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection