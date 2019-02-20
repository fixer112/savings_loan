@extends('layouts.app')
@php

function naira($number){
    return "₦". number_format($number, 2);

    }

@endphp

@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
@endsection

@section('title')
HONEYPAYS | Add Verification
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
            
                <div class="card-header"><strong>ADD NEW VERIFICATION</strong></div>

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

<form class="form-horizontal" method="POST" action="/verify/add" files="true" enctype="multipart/form-data">
                        {{ csrf_field() }}


                        <div class="form-group{{ $errors->has('accno') ? ' has-error' : '' }}">
                            <label for="accno" class="col-md-4 control-label">Account Number</label>

                            <div class="col-md-6">
                                <input id="accno" type="text" class="form-control" name="accno" value="{{ old('accno') }}" required autofocus>

                                @if ($errors->has('accno'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('accno') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('loan') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Loan Category</label>

                            <div class="col-md-6">
                                <select id="loan" type="text" class="form-control" name="loan" required autofocus>

                                <option value="" {{old('loan')== "" ? "selected": ""}}>Choose</option>

                                <option value="30000" {{old('loan')== "30000" ? "selected": ""}}>{{naira(30000)}}</option>

                                <option value="60000" {{old('loan')== "60000" ? "selected": ""}}>{{naira(60000)}}</option>

                                <option value="150000" {{old('loan')== "150000" ? "selected": ""}}>{{naira(150000)}}</option>

                                <option value="300000" {{old('loan')== "300000" ? "selected": ""}}>{{naira(300000)}}</option>

                               
                                </select>

                                @if ($errors->has('loan'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('loan') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        

                        <div class="form-group{{ $errors->has('form1') ? ' has-error' : '' }}">
                            <label for="form1" class="col-md-4 control-label">Form1</label>

                        <div class="col-md-6">
                                <input id="form1" type="file" class="form-control" name="form1" accept="image/*" value="{{ old('form1') }}" required>

                                @if ($errors->has('form1'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('form1') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('form2') ? ' has-error' : '' }}">
                            <label for="form2" class="col-md-4 control-label">Form2</label>

                        <div class="col-md-6">
                                <input id="form2" type="file" class="form-control" name="form2" accept="image/*" value="{{ old('form2') }}" required>

                                @if ($errors->has('form2'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('form2') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('form3') ? ' has-error' : '' }}">
                            <label for="form3" class="col-md-4 control-label">Form3</label>

                        <div class="col-md-6">
                                <input id="form3" type="file" class="form-control" name="form3" accept="image/*" value="{{ old('form3') }}" required>

                                @if ($errors->has('form3'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('form3') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('form4') ? ' has-error' : '' }}">
                            <label for="form4" class="col-md-4 control-label">Form4</label>

                        <div class="col-md-6">
                                <input id="form4" type="file" class="form-control" name="form4" accept="image/*" value="{{ old('form3') }}">

                                @if ($errors->has('form4'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('form4') }}</strong>
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
    </div>
</div>

@endsection