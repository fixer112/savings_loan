@extends('layouts.app')
@php
$id = Auth::user()->id;
@endphp

@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
@endsection

@section('title')
HONEYPAYS | Edit customer
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
            
                <div class="card-header"><strong>EDIT CUSTOMER</strong></div>

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

<form class="form-horizontal" method="POST" action="/admin/customer/edit/{{$user->id}}" files="true" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{method_field('PUT')}}


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Full Name</label>

                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}"  autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mentor') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Branch Code</label>

                            <div class="col-md-6">
                                <select id="mentor" type="text" class="form-control" name="mentor" required autofocus>
                                @if(!in_array($user->referal, $data['nobs']))
                                <option value="" selected disabled>{{$user->referal}}</option>
                                @endif
                                @foreach($data['nobs'] as $nob)

                                <option value="{{$nob}}" {{$user->referal== $nob ? "selected": ""}}>{{$nob}}</option>

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
                                <input id="username" type="text" class="form-control" name="username" value="{{ $user->username }}"  autofocus>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('resi_add') ? ' has-error' : '' }}">
                            <label for="resi_add" class="col-md-4 control-label">Residential Address</label>

                            <div class="col-md-10">
                                <input id="resi_add" type="text" class="form-control" name="resi_add" value="{{ $user->resi_add }}" >

                                @if ($errors->has('resi_add'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('resi_add') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('busi_add') ? ' has-error' : '' }}">
                            <label for="busi_add" class="col-md-4 control-label">Business Address</label>

                            <div class="col-md-10">
                                <input id="resi_add" type="text" class="form-control" name="busi_add" value="{{ $user->busi_add }}" >

                                @if ($errors->has('busi_add'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('busi_add') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('nature_add') ? ' has-error' : '' }}">
                            <label for="nature_add" class="col-md-4 control-label">Nature of Business</label>

                            <div class="col-md-10">
                                <input id="nature_add" type="text" class="form-control" name="nature_add" value="{{ $user->nature_add }}" >

                                @if ($errors->has('nature_add'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nature_add') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('passport') ? ' has-error' : '' }}">
                            <label for="passport" class="col-md-4 control-label">Passport</label>

                        <div class="col-md-10">
                                <input id="passport" type="file" class="form-control" name="passport" accept="image/*" value="{{ $user->passport }}" >

                                @if ($errors->has('passport'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('passport') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('idcard') ? ' has-error' : '' }}">
                            <label for="idcard" class="col-md-4 control-label">Identity Card</label>

                        <div class="col-md-10">
                                <input id="idcard" type="file" class="form-control" name="idcard" accept="image/*" value="{{ old('idcard') }}" >

                                @if ($errors->has('idcard'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('idcard') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kin_name') ? ' has-error' : '' }}">
                            <label for="kin_name" class="col-md-4 control-label">Next of Kin Name</label>

                            <div class="col-md-10">
                                <input id="kin_name" type="text" class="form-control" name="kin_name" value="{{ $user->kin_name }}" >

                                @if ($errors->has('kin_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('kin_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kin_add') ? ' has-error' : '' }}">
                            <label for="kin_add" class="col-md-4 control-label">Next of Kin Address</label>

                            <div class="col-md-10">
                                <input id="kin_add" type="text" class="form-control" name="kin_add" value="{{ $user->kin_add }}" >

                                @if ($errors->has('kin_add'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('kin_add') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kin_relation') ? ' has-error' : '' }}">
                            <label for="kin_relation" class="col-md-4 control-label">Next of Kin Relationship</label>

                            <div class="col-md-10">
                                <input id="kin_relation" type="text" class="form-control" name="kin_relation" value="{{ $user->kin_relation }}" >

                                @if ($errors->has('kin_relation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('kin_relation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kin_number') ? ' has-error' : '' }}">
                            <label for="kin_number" class="col-md-4 control-label">Next of Kin Mobile Number</label>

                            <div class="col-md-10">
                                <input id="kin_number" type="text" class="form-control" name="kin_number" value="{{ $user->kin_number }}" placeholder="234xxxxxxxxxx" >

                                @if ($errors->has('kin_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('kin_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kin_verify') ? ' has-error' : '' }}">
                            <label for="kin_verify" class="col-md-4 control-label">Next of Kin Verify</label>

                            <div class="col-md-10">
                                <select id="kin_verify" type="option" class="form-control" name="kin_verify" value="{{ $user->kin_verify }}" >
                                <option value="Not Approved" {{$user->kin_verify == 'Not Approved' ? 'selected':''}}>Not Approved</option>
                                <option value="Approved" {{$user->kin_verify == 'Approved' ? 'selected':''}}>Approved</option>
                                </select>

                                @if ($errors->has('kin_verify'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('kin_verify') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kin_passport') ? ' has-error' : '' }}">
                            <label for="kin_passport" class="col-md-4 control-label">Next of Kin Passport</label>

                        <div class="col-md-10">
                                <input id="kin_passport" type="file" class="form-control" name="kin_passport" accept="image/*" value="{{ old('kin_passport') }}" >

                                @if ($errors->has('kin_passport'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('kin_passport') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara1_name') ? ' has-error' : '' }}">
                            <label for="gara1_name" class="col-md-4 control-label">First Guarantor Name</label>

                            <div class="col-md-10">
                                <input id="gara1_name" type="text" class="form-control" name="gara1_name" value="{{ $user->gara1_name }}" >

                                @if ($errors->has('gara1_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gara1_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara1_add') ? ' has-error' : '' }}">
                            <label for="gara1_add" class="col-md-4 control-label">First Guarantor Address</label>

                            <div class="col-md-10">
                                <input id="gara1_add" type="text" class="form-control" name="gara1_add" value="{{ $user->gara1_add }}" >

                                @if ($errors->has('gara1_add'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gara1_add') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara1_occupation') ? ' has-error' : '' }}">
                            <label for="gara1_occupation" class="col-md-4 control-label">First Guarantor Occupation</label>

                            <div class="col-md-10">
                                <input id="gara1_occupation" type="text" class="form-control" name="gara1_occupation" value="{{ $user->gara1_occupation }}" >

                                @if ($errors->has('gara1_occupation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gara1_occupation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara1_number') ? ' has-error' : '' }}">
                            <label for="gara1_number" class="col-md-4 control-label">First Guarantor Mobile Number</label>

                            <div class="col-md-10">
                                <input id="gara1_number" type="text" class="form-control" name="gara1_number" placeholder="234xxxxxxxxxx" value="{{ $user->gara1_number }}" >

                                @if ($errors->has('gara1_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gara1_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara1_verify') ? ' has-error' : '' }}">
                            <label for="gara1_verify" class="col-md-4 control-label">First Guarantor Verfy</label>

                            <div class="col-md-10">
                                <select id="gara1_verify" type="option" class="form-control" name="gara1_verify" value="{{ old('gara1_verify') }}" >
                                <option value="Not Approved" {{$user->gara1_verify == 'Not Approved' ? 'selected':''}}>Not Approved</option>
                                <option value="Approved" {{$user->gara1_verify == 'Approved' ? 'selected':''}}>Approved</option>
                                </select>

                                @if ($errors->has('gara1_verify'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gara1_verify') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara1_passport') ? ' has-error' : '' }}">
                            <label for="gara1_passport" class="col-md-4 control-label">First Guarantor Passport</label>

                        <div class="col-md-10">
                                <input id="gara1_passport" type="file" class="form-control" name="gara1_passport" accept="image/*" value="{{ old('gara1_passport') }}" >

                                @if ($errors->has('gara1_passport'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gara1_passport') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara2_name') ? ' has-error' : '' }}">
                            <label for="gara2_name" class="col-md-4 control-label">Second Guarantor Name</label>

                            <div class="col-md-10">
                                <input id="gara2_name" type="text" class="form-control" name="gara2_name" value="{{ $user->gara2_name }}" >

                                @if ($errors->has('gara2_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gara2_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara2_add') ? ' has-error' : '' }}">
                            <label for="gara2_add" class="col-md-4 control-label">Second Guarantor Address</label>

                            <div class="col-md-10">
                                <input id="gara2_add" type="text" class="form-control" name="gara2_add" value="{{ $user->gara2_add }}" >

                                @if ($errors->has('gara2_add'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gara2_add') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara2_occupation') ? ' has-error' : '' }}">
                            <label for="gara2_occupation" class="col-md-4 control-label">Second Guarantor Occupation</label>

                            <div class="col-md-10">
                                <input id="gara2_occupation" type="text" class="form-control" name="gara2_occupation" value="{{ $user->gara2_occupation }}" >

                                @if ($errors->has('gara2_occupation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gara2_occupation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara2_number') ? ' has-error' : '' }}">
                            <label for="gara2_number" class="col-md-4 control-label">Second Guarantor Mobile Number</label>

                            <div class="col-md-10">
                                <input id="gara2_number" type="text" class="form-control" name="gara2_number" placeholder="234xxxxxxxxxx" value="{{ $user->gara2_number }}" >

                                @if ($errors->has('gara2_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gara2_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara2_verify') ? ' has-error' : '' }}">
                            <label for="gara2_verify" class="col-md-4 control-label">Second Guarantor Verfy</label>

                            <div class="col-md-10">
                                <select id="gara2_verify" type="text" class="form-control" name="gara2_verify" value="{{ old('gara2_verify') }}" >
                                <option value="Not Approved" {{$user->gara2_verify == 'Not Approved' ? 'selected':''}}>Not Approved</option>
                                <option value="Approved" {{$user->gara2_verify == 'Approved' ? 'selected':''}}>Approved</option>
                                </select>

                                @if ($errors->has('gara2_verify'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gara2_verify') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara2_passport') ? ' has-error' : '' }}">
                            <label for="gara2_passport" class="col-md-4 control-label">Guarantor Passport</label>

                        <div class="col-md-10">
                                <input id="gara2_passport" type="file" class="form-control" name="gara2_passport" accept="image/*" value="{{ old('gara2_passport') }}" >

                                @if ($errors->has('gara2_passport'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gara2_passport') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Edit Customer
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>

                    <div class="col-md">

                    <form class="form-horizontal" method="POST" action="/admin/customer/edit/{{$user->id}}" files="true" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{method_field('PUT')}}

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
                                    Change Customer Password
                                </button>
                            </div>
                        </div>
                    </form>

                    <form class="form-horizontal" method="POST" action="/admin/customer/edit/{{$user->id}}" files="true" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{method_field('PUT')}}

                    <div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
                            <label for="number" class="col-md-4 control-label">Mobile Number</label>

                            <div class="col-md-10">
                                <input id="number" type="tel" class="form-control" name="number" value="{{ $user->number}}" placeholder="234xxxxxxxxxx"  autofocus>

                                @if ($errors->has('number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Change Customer Number
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