@extends('layouts.app')
@php
$id = Auth::user()->id;
@endphp

@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
@endsection

@section('title')
HONEYPAYS |  Register
@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function(){
    $("#type").change(function(){
        if ($("#type").val() == 'loan'){
        $('#category').removeAttr("disabled" );
        $('#due_date').removeAttr("disabled" );
         $('#category').attr('required', 'required');
        $('#due_date').attr('required', 'required');
        $('#amount').attr('disabled', 'disabled');

        }else{
        $('#category').attr('disabled', 'disabled');
        $('#due_date').attr('disabled', 'disabled');
        $('#category').removeAttr("required" );
        $('#due_date').removeAttr("required" );
        $('#amount').removeAttr("disabled" );
        }
    });
});
</script>

@endsection

@section('menu')

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            
                <div class="card-header"><strong>ADD CUSTOMER TRANSACTION</strong></div>

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

<form class="form-horizontal" method="POST" action="/admin2/verifytrans">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Acc No</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label for="amount" class="col-md-4 control-label">Amount</label>

                            <div class="col-md-6">
                                <input id="amount" type="number" class="form-control" name="amount" value="{{ old('amount')}}" required autofocus>

                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <label for="type" class="col-md-4 control-label">Transaction Type</label>

                            <div class="col-md-6">
                                <select id="type" type="option" class="form-control" name="type" value="{{ old('type') }}" required autofocus>

                                <option value="deposit">DEPOSIT</option>
                                <option value="withdraw">WITHDRAW</option>
                                <option value="default_fee">DEFAULT FEE</option>
                                <option value="interest_fee">INTEREST FEE</option>
                                <option value="loan">LOAN</option>
                                </select>

                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('recieved') ? ' has-error' : '' }}">
                            <label for="recieved" class="col-md-4 control-label">Recieved By</label>

                            <div class="col-md-6">
                                <input id="recieved" type="text" class="form-control" value="{{ old('recieved') }}" name="recieved" required>

                                @if ($errors->has('recieved'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('recieved') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-4 control-label">Transaction Description</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" value="{{ old('description') }}" required>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                            <label for="category" class="col-md-4 control-label">Loan Category</label>

                            <div class="col-md-6">
                                <select id="category" type="option" class="form-control" name="category" disabled>
                                <option value="30000">30,000</option>
                                <option value="60000">60,000</option>
                                <option value="150000">150,000</option>
                                <option value="300000">300,000</option>
                                </select>

                                @if ($errors->has('category'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('due_date') ? ' has-error' : '' }}">
                            <label for="due_date" class="col-md-4 control-label">Loan Due Date</label>

                            <div class="col-md-6">
                                <input id="due_date" type="text" class="form-control" name="due_date" placeholder="dd/mm/yy e.g 21/03/18" value="{{ old('due_date') }}" disabled>

                                @if ($errors->has('due_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('due_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    SUBMIT
                                </button>
                            </div>
                        </div>
                    </form>
                   </div>
          </div>
        </div>
    </div>
</div>

@endsection