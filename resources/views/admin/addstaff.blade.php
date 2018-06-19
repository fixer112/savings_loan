@extends('layouts.app')
@php
$id = Auth::user()->id;
@endphp

@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
@endsection

@section('title')
HONEYPAYS | Add/Edit Staff
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
            
                <div class="card-header"><strong>ADD/EDIT STAFF</strong></div>

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

<form class="form-horizontal" method="POST" action="/admin/createstaff" files="true" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Full Name</label>

                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"  autofocus required>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mentor') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Assign Branch Code</label>

                            <div class="col-md-6">
                                <select id="mentor" type="text" class="form-control" name="mentor" required autofocus>

                                <option value="" {{old('mentor')== "" ? "selected": ""}}>Choose</option>

                                @foreach($data['nobs'] as $nob)

                                <option value="{{$nob}}" {{old('mentor')== $nob ? "selected": ""}}>{{$nob}}</option>

                                @endforeach
                                    
                                </select>

                                @if ($errors->has('mentor'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mentor') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Account Number</label>

                            <div class="col-md-10">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}"  autofocus required="">

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password_confirmation" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Add Staff
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>

                    <div class="col-md">

                    <form class="form-horizontal" method="POST" action="/admin/editstaff" files="true" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{method_field('PUT')}}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Account Number</label>

                            <div class="col-md-10">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}"  autofocus required>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-10">
                                <input id="password" type="password" class="form-control" name="password" >

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                    </div>
                    <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Change Staff Password
                                </button>
                            </div>
                        </div>
                    </form>

                    </div>

                   </div>
          </div>
        </div>
    </div>
</div>

@endsection