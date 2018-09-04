@extends('layouts.app')
@php
$id = Auth::user()->id;
@endphp

@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
@endsection

@section('title')
HONEYPAYS | Register Customer
@endsection

@section('script')
{{-- <script src="https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js"></script> --}}
@endsection
@section('js')

@endsection

@section('menu')

@endsection

@section('content')
<div id="app" class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div id="cus" class="card-header"><strong>ADD NEW CUSTOMER</strong></div>

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

                

                    <form v-on:keydown.enter.prevent.self class="form-horizontal" method="POST" action="/admin/register" files="true" enctype="multipart/form-data">
                        {{ csrf_field() }}


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Full Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mentor') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Branch Number</label>
                           
                            <div class="col-md-6">
                                <select id="mentor" v-model="branch" @change="hide_it" type="text" class="form-control" name="mentor" required autofocus>

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

                            <div class="col-md-6">
                                <input id="username" type="text" readonly ="true" class="form-control" name="username" :value="acc_num" required autofocus>

                                @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Account Type</label>

                            <div class="col-md-6">
                                <select id="type" v-model= "acc_type" @change="hide_it" class="form-control" name="type" required autofocus>

                                    <option value="" {{old('type')== "" ? "selected": ""}}>Choose</option>

                                    <option value="savings" {{old('type')== 'savings' ? "selected": ""}}>Savings</option>

                                    <option value="loan" {{old('type')== 'loan' ? "selected": ""}}>Loan</option>
                                    
                                </select>

                                @if ($errors->has('type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('loan_cat') ? ' has-error' : '' }}">
                            <label for="loan_cat" class="col-md-4 control-label">Loan Category</label>

                            <div class="col-md-6">
                                <select id="loan_cat" v-model= "loan_cat" @change="hide_it" :required="acc_type == 'loan'" :disabled="acc_type != 'loan'" class="form-control" name="loan_cat" autofocus>

                                    <option value="" {{old('loan_cat')== "" ? "selected": ""}}>Choose</option>

                                    <option value="010" {{old('loan_cat')== '010' ? "selected": ""}}>30k</option>

                                    <option value="020" {{old('loan_cat')== '020' ? "selected": ""}}>60k</option>
                                    
                                    <option value="050" {{old('loan_cat')== '050' ? "selected": ""}}>150k</option>

                                    <option value="100" {{old('loan_cat')== '100' ? "selected": ""}}>300k</option>
                                </select>

                                @if ($errors->has('type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
                            <label for="number" class="col-md-4 control-label">Mobile Number</label>

                            <div class="col-md-6">
                                <input id="number" v-model="mobile" @change="hide_it" @keydown="hide_it" type="tel" class="form-control" name="number" value="{{ old('number')}}" placeholder="234xxxxxxxxxx" required autofocus>

                                @if ($errors->has('number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('number') }}</strong>
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

                        <div class="form-group{{ $errors->has('resi_add') ? ' has-error' : '' }}">
                            <label for="resi_add" class="col-md-4 control-label">Residential Address</label>

                            <div class="col-md-6">
                                <input id="resi_add" type="text" class="form-control" name="resi_add" value="{{ old('resi_add') }}" required>

                                @if ($errors->has('resi_add'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('resi_add') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('busi_add') ? ' has-error' : '' }}">
                            <label for="busi_add" class="col-md-4 control-label">Business Address</label>

                            <div class="col-md-6">
                                <input id="busi_add" type="text" class="form-control" name="busi_add" value="{{ old('busi_add') }}" required>

                                @if ($errors->has('busi_add'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('busi_add') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('nature_add') ? ' has-error' : '' }}">
                            <label for="nature_add" class="col-md-4 control-label">Nature of Business</label>

                            <div class="col-md-6">
                                <input id="nature_add" type="text" class="form-control" name="nature_add" value="{{ old('nature_add') }}" required>

                                @if ($errors->has('nature_add'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nature_add') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('passport') ? ' has-error' : '' }}">
                            <label for="passport" class="col-md-4 control-label">Passport</label>

                            <div class="col-md-6">
                                <input id="passport" type="file" class="form-control" name="passport" accept="image/*" value="{{ old('passport') }}" required>

                                @if ($errors->has('passport'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('passport') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('idcard') ? ' has-error' : '' }}">
                            <label for="idcard" class="col-md-4 control-label">Identity Card</label>

                            <div class="col-md-6">
                                <input id="idcard" type="file" class="form-control" name="idcard" accept="image/*" value="{{ old('idcard') }}" required>

                                @if ($errors->has('idcard'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('idcard') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kin_name') ? ' has-error' : '' }}">
                            <label for="kin_name" class="col-md-4 control-label">Next of Kin Name</label>

                            <div class="col-md-6">
                                <input id="kin_name" type="text" class="form-control" name="kin_name" value="{{ old('kin_name') }}" required>

                                @if ($errors->has('kin_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('kin_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kin_add') ? ' has-error' : '' }}">
                            <label for="kin_add" class="col-md-4 control-label">Next of Kin Address</label>

                            <div class="col-md-6">
                                <input id="kin_add" type="text" class="form-control" name="kin_add" value="{{ old('kin_add') }}" required>

                                @if ($errors->has('kin_add'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('kin_add') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kin_relation') ? ' has-error' : '' }}">
                            <label for="kin_relation" class="col-md-4 control-label">Next of Kin Relationship</label>

                            <div class="col-md-6">
                                <input id="kin_relation" type="text" class="form-control" name="kin_relation" value="{{ old('kin_relation') }}" required>

                                @if ($errors->has('kin_relation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('kin_relation') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kin_number') ? ' has-error' : '' }}">
                            <label for="kin_number" class="col-md-4 control-label">Next of Kin Mobile Number</label>

                            <div class="col-md-6">
                                <input id="kin_number" type="text" class="form-control" name="kin_number" value="{{ old('kin_number') }}" placeholder="234xxxxxxxxxx" required>

                                @if ($errors->has('kin_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('kin_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('kin_verify') ? ' has-error' : '' }}">
                            <label for="kin_verify" class="col-md-4 control-label">Next of Kin Verify</label>

                            <div class="col-md-6">
                                <select id="kin_verify" type="option" class="form-control" name="kin_verify" value="{{ old('kin_verify') }}" required>
                                    <option selected="true" value="Not Approved">Not Approved</option>
                                    <option value="Approved">Approved</option>
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

                            <div class="col-md-6">
                                <input id="kin_passport" type="file" class="form-control" name="kin_passport" accept="image/*" value="{{ old('kin_passport') }}" required>

                                @if ($errors->has('kin_passport'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('kin_passport') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara1_name') ? ' has-error' : '' }}">
                            <label for="gara1_name" class="col-md-4 control-label">First Guarantor Name</label>

                            <div class="col-md-6">
                                <input id="gara1_name" type="text" class="form-control" name="gara1_name" value="{{ old('gara1_name') }}" required>

                                @if ($errors->has('gara1_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gara1_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara1_add') ? ' has-error' : '' }}">
                            <label for="gara1_add" class="col-md-4 control-label">First Guarantor Address</label>

                            <div class="col-md-6">
                                <input id="gara1_add" type="text" class="form-control" name="gara1_add" value="{{ old('gara1_add') }}" required>

                                @if ($errors->has('gara1_add'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gara1_add') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara1_occupation') ? ' has-error' : '' }}">
                            <label for="gara1_occupation" class="col-md-4 control-label">First Guarantor Occupation</label>

                            <div class="col-md-6">
                                <input id="gara1_occupation" type="text" class="form-control" name="gara1_occupation" value="{{ old('gara1_occupation') }}" required>

                                @if ($errors->has('gara1_occupation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gara1_occupation') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara1_number') ? ' has-error' : '' }}">
                            <label for="gara1_number" class="col-md-4 control-label">First Guarantor Mobile Number</label>

                            <div class="col-md-6">
                                <input id="gara1_number" type="text" class="form-control" name="gara1_number" placeholder="234xxxxxxxxxx" value="{{ old('gara1_number') }}" required>

                                @if ($errors->has('gara1_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gara1_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara1_verify') ? ' has-error' : '' }}">
                            <label for="gara1_verify" class="col-md-4 control-label">First Guarantor Verfy</label>

                            <div class="col-md-6">
                                <select id="gara1_verify" type="option" class="form-control" name="gara1_verify" value="{{ old('gara1_verify') }}" required>
                                    <option selected="true" value="Not Approved">Not Approved</option>
                                    <option value="Approved">Approved</option>
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

                            <div class="col-md-6">
                                <input id="gara1_passport" type="file" class="form-control" name="gara1_passport" accept="image/*" value="{{ old('gara1_passport') }}" required>

                                @if ($errors->has('gara1_passport'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gara1_passport') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara2_name') ? ' has-error' : '' }}">
                            <label for="gara2_name" class="col-md-4 control-label">Second Guarantor Name</label>

                            <div class="col-md-6">
                                <input id="gara2_name" type="text" class="form-control" name="gara2_name" value="{{ old('gara2_name') }}" required>

                                @if ($errors->has('gara2_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gara2_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara2_add') ? ' has-error' : '' }}">
                            <label for="gara2_add" class="col-md-4 control-label">Second Guarantor Address</label>

                            <div class="col-md-6">
                                <input id="gara2_add" type="text" class="form-control" name="gara2_add" value="{{ old('gara2_add') }}" required>

                                @if ($errors->has('gara2_add'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gara2_add') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara2_occupation') ? ' has-error' : '' }}">
                            <label for="gara2_occupation" class="col-md-4 control-label">Second Guarantor Occupation</label>

                            <div class="col-md-6">
                                <input id="gara2_occupation" type="text" class="form-control" name="gara2_occupation" value="{{ old('gara2_occupation') }}" required>

                                @if ($errors->has('gara2_occupation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gara2_occupation') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara2_number') ? ' has-error' : '' }}">
                            <label for="gara2_number" class="col-md-4 control-label">Second Guarantor Mobile Number</label>

                            <div class="col-md-6">
                                <input id="gara2_number" type="text" class="form-control" name="gara2_number" placeholder="234xxxxxxxxxx" value="{{ old('gara2_number') }}" required>

                                @if ($errors->has('gara2_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gara2_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gara2_verify') ? ' has-error' : '' }}">
                            <label for="gara2_verify" class="col-md-4 control-label">Second Guarantor Verfy</label>

                            <div class="col-md-6">
                                <select id="gara2_verify" type="text" class="form-control" name="gara2_verify" value="{{ old('gara2_verify') }}" required>
                                    <option selected="true" value="Not Approved">Not Approved</option>
                                    <option value="Approved">Approved</option>
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

                            <div class="col-md-6">
                                <input id="gara2_passport" type="file" class="form-control" name="gara2_passport" accept="image/*" value="{{ old('gara2_passport') }}" required>

                                @if ($errors->has('gara2_passport'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gara2_passport') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" :disabled="hide" class="btn btn-primary" {{-- v-if="can_submit" --}}>
                                    Register
                                </button>

                                <button class="btn btn-primary" @click.prevent="get_num">
                                    Get Account Number
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