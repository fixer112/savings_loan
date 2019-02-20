@extends('layouts.app')
@section('css')
<link href="{{ asset('css/cus_style.css') }}" rel="stylesheet">
<style type="text/css">

        .img img {
            width: 30%;
   
        }
        .img2, .img{
            padding-bottom: 15px;
        }
        .img2 img {
            width: 38%;
        }
    </style>
@endsection

@section('title')
HONEYPAYS | Collateral- {{Auth::user()->username}}
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
                <div class="card-header"><strong>KIN/GARANTOR</strong></div>

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
                        <div class="col-md kin">
                        <center><b>NEXT OF KIN</b></center>
                        </br>
                        <div class="img2">
                       <img src="/{{ Auth::user()->kin_passport }}">
                       </div>
                        <p>Name: {{ Auth::user()->kin_name }}</p>
                        <p>Address: {{ Auth::user()->kin_add }}</p>
                        <p>relationship: {{ Auth::user()->kin_relation }}</p>
                        <p>Mobile number: {{ Auth::user()->kin_number }}</p>
                        <p>Verification remark: <strong style="color: {{ Auth::user()->kin_verify =='Approved' ? 'green' : 'red' }}" >{{ Auth::user()->kin_verify }} </strong></p>
                        </div>
                        <div class="col-md garantor1">
                        <center><b>GUARANTOR ONE</b></center>
                        </br>
                        <div class="img2">
                        <img src="/{{ Auth::user()->gara1_passport }}">
                        </div>
                        <p>Name: {{ Auth::user()->gara1_name }}</p>
                        <p>Address: {{ Auth::user()->gara1_add }}</p>
                        <p>Occupation: {{ Auth::user()->gara1_occupation }}</p>
                        <p>Mobile number: {{ Auth::user()->gara1_number }}</p>
                        <p>Verification remark: <strong style="color: {{ Auth::user()->gara1_verify =='Approved' ? 'green' : 'red' }}" >{{ Auth::user()->gara1_verify }} </strong></p>
                    </div>
                    <div class="col-md garantor2">
                    <center><b>GUARANTOR TWO</b></center>
                    </br>
                    <div class="img2">
                    <img src="/{{ Auth::user()->gara2_passport }}">
                    </div>
                        <p>Name: {{ Auth::user()->gara2_name }}</p>
                        <p>Address: {{ Auth::user()->gara2_add }}</p>
                        <p>Occupation: {{ Auth::user()->gara2_occupation }}</p>
                        <p>Mobile number: {{ Auth::user()->gara2_number }}</p>
                        <p>Verification remark: <strong style="color: {{ Auth::user()->gara2_verify =='Approved' ? 'green' : 'red' }}" >{{ Auth::user()->gara2_verify }} </strong></p>
                    </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
