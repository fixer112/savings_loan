@extends('layouts.app')
@php
$id = Auth::user()->id;
@endphp

@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

@endsection

@section('title')
HONEYPAYS | Search
@endsection

@section('js')
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
@endsection

@section('menu')

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            
                <div class="card-header"><strong>SEARCH CUSTOMER</strong></div>

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

                    {{-- <form class="form-horizontal" method="POST" action="/staff/search">
                        {{ csrf_field() }}
                       
                        <div class="form-group{{ $errors->has('search') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                <input id="search" type="text" class="form-control" name="search" value="{{session('search') ? session('search') : 'Search by customer username or mobile number'}}" required>

                                @if ($errors->has('search'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('search') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                         <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form> --}}

                   {{--  @if ($_POST) --}}
                    @if ($searchs->count()>0)
          <div class="table-responsive">
            <table class="search table table-bordered" id="" width="100%" cellspacing="0">
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
              @foreach ($searchs as $search)
                <tr>
                
                  <td> {{$search->username}} </td>
                  <td> {{$search->name}} </td>
                  
                  <td> {{$search->number}} </td>
                  <td> {{$search->savings_balance}} </td>
                  <td> {{$search->loan_balance}} </td>
                  <td><button class="btn btn-success"><i class="fa fa-eye"></i><a href="/staff/customer/{{$search->id}}">View</a></button>
                  {{-- <button class="btn btn-danger"><i class="fa fa-edit"></i><a href="/staff/customer/edit/{{$search->id}}">Edit</a></button> --}}
                  </td>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
         @else
         <div class="alert alert-danger">No Customer Found</div>
        @endif
       {{--  @endif --}}

                   </div>
          </div>
        </div>
    </div>
</div>

@endsection