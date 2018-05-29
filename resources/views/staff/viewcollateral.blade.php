@extends('layouts.app')
@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
<style type="text/css">
        body{
            background-color: blue;
        }
        .card-header{
            color: blue;
        }
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
HONEYPAYS | Customer Collateral- {{$user->username}}
@endsection

@section('menu')

@endsection

@section('content')
@php
$id = $user->id;
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
                       <img src="/{{ $user->kin_passport }}">
                       </div>
                        <p>Name: {{ $user->kin_name }}</p>
                        <p>Address: {{ $user->kin_add }}</p>
                        <p>relationship: {{ $user->kin_relation }}</p>
                        <p>Mobile number: {{ $user->kin_number }}</p>
                        <p>Verification remark: <strong style="color: {{ $user->kin_verify =='Approved' ? 'green' : 'red' }}" >{{ $user->kin_verify }} </strong></p>
                        </div>
                        <div class="col-md garantor1">
                        <center><b>GARANTOR ONE</b></center>
                        </br>
                        <div class="img2">
                        <img src="/{{ $user->gara1_passport }}">
                        </div>
                        <p>Name: {{ $user->gara1_name }}</p>
                        <p>Address: {{ $user->gara1_add }}</p>
                        <p>Occupation: {{ $user->gara1_occupation }}</p>
                        <p>Mobile number: {{ $user->gara1_number }}</p>
                        <p>Verification remark: <strong style="color: {{ $user->gara1_verify =='Approved' ? 'green' : 'red' }}" >{{ $user->gara1_verify }} </strong></p>
                    </div>
                    <div class="col-md garantor2">
                    <center><b>GARANTOR TWO</b></center>
                    </br>
                    <div class="img2">
                    <img src="/{{ $user->gara2_passport }}">
                    </div>
                        <p>Name: {{ $user->gara2_name }}</p>
                        <p>Address: {{ $user->gara2_add }}</p>
                        <p>Occupation: {{ $user->gara2_occupation }}</p>
                        <p>Mobile number: {{ $user->gara2_number }}</p>
                        <p>Verification remark: <strong style="color: {{ $user->gara2_verify =='Approved' ? 'green' : 'red' }}" >{{ $user->gara2_verify }} </strong></p>
                    </div>
                    </div>

                    <p><button class="btn btn-info"><i class="fa fa-eye"></i><a href="/staff/customer/{{$user->id}}">view Dashboard</a></button></p>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
