@extends('layouts.app')
@php
$id = Auth::user()->id;
@endphp

@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
@endsection

@section('title')
HONEYPAYS | Transfer
@endsection

@section('js')


@endsection

@section('menu')

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header"><strong>ADD TRANSFER</strong></div>

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

                    <form class="form-horizontal" method="POST" action="/transfer" id="tran">
                        {{ csrf_field() }}

                        @if(Auth::user()->role != 'customer')
                        <div class="form-group{{ $errors->has('from') ? ' has-error' : '' }}">
                            <label for="from" class="col-md-4 control-label">From Acc No</label>

                            <div class="col-md-12">
                                <input id="from" type="text" class="form-control" name="from" value="{{ old('from') }}"
                                    required autofocus>

                                @if ($errors->has('from'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('from') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        @else

                        <input id="from" type="text" class="form-control" name="from" value="{{Auth::user()->username}}"
                            required hidden autofocus>
                        @endif



                        <div class="form-group{{ $errors->has('to') ? ' has-error' : '' }}">
                            <label for="from" class="col-md-4 control-label">To Acc No</label>

                            <div class="col-md-12">
                                <input id="to" type="text" class="form-control" name="to" value="{{ old('to') }}"
                                    required autofocus>

                                @if ($errors->has('to'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('to') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label for="amount" class="col-md-4 control-label">Amount</label>

                            <div class="col-md-12">
                                <input id="amount" type="number" class="form-control" name="amount"
                                    value="{{ old('amount')}}" required autofocus>

                                @if ($errors->has('amount'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('amount') }}</strong>
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