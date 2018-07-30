@extends('layouts.app')
@php
$id = Auth::user()->id;
@endphp

@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
<link href="{{ asset('fonts/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
@endsection

@section('title')
HONEYPAYS | Referals
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
            
                <div class="card-header"><strong>Branch Referals</strong></div>

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

                <div class="container">
  {{-- <h2>Cards Columns</h2>
  <p>The .card-columns class creates a masonry-like grid of cards. The layout will automatically adjust as you insert more cards.</p>
  <p><strong>Note:</strong> The cards are displayed vertically on small screens (less than 576px):</p> --}}
  <div class="row">
  <div class="card-columns">
    <div class="card text-white bg-primary">
      <div class="card-body text-center">
        <h4 class="card-title">Branch No</h4>
        <p><strong>{{Auth::user()->mentor}}</p></strong>
      </div>
    </div>
    
    <div class="card text-white bg-success">
      <div class="card-body text-center">
       <h4 class="card-title">Total Branch Customers</h4>
        <p><strong>{{count($referals)}}</p></strong>
       </div>
      </div>
    </div>
    </div>
  </div>
</div>



    <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Customer Acc No</th>
                  <th>Customer Name</th>
                  
                  <th>Mobile Number</th>
                  <th>Savings Balance</th>
                  <th>Loan Balance</th>
                  <th>Actions</th>
                </tr>
              </thead>
              
              <tbody>
              @foreach ($referals as $referal)
                <tr>
                
                  <td> {{$referal->username}} </td>
                  <td> {{$referal->name}} </td>
                  
                  <td> {{$referal->number}} </td>
                  <td> {{$referal->savings_balance}} </td>
                  <td> {{$referal->loan_balance}} </td>
                  <td><a href="/{{Auth::user()->role}}/customer/{{$referal->id}}"><button class="btn btn-success"><i class="fa fa-eye"></i>View</button></a>
                    @if(Auth::user()->role == 'admin')
                    <a href="/admin/customer/edit/{{$referal->id}}"><button class="btn btn-danger"><i class="fa fa-edit"></i>Edit</button></a>
                    @endif
                  </td>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>

                   </div>
          </div>
        </div>
    </div>
</div>

@endsection