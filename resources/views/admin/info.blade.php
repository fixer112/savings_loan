@extends('layouts.app')
@php
$id = Auth::user()->id;
@endphp

@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
@endsection

@section('title')
HONEYPAYS | {{ Auth::user()->name }}
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
            
                <div class="card-header"><strong>Dashboard</strong></div>

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

                   

            <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="customer_active-tab" data-toggle="tab" href="#customer_active" role="tab" aria-controls="customer_active" aria-selected="true"><b style="color: green">{{$customer->where('role', '=', 'customer')->where('suspend', '=', 'no')->get()->count()}} Active CUstomer</b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="customer_suspend-tab" data-toggle="tab" href="#customer_suspend" role="tab" aria-controls="customer_suspend" aria-selected="false"><b style="color: red">{{$customer->where('role', '=', 'customer')->where('suspend', '=', 'yes')->get()->count()}} Suspended Customer </b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="staff_active-tab" data-toggle="tab" href="#staff_active" role="tab" aria-controls="staff_active" aria-selected="false"><b style="color: green">{{$customer->where('role', '=', 'staff')->where('suspend', '=', 'no')->get()->count()}} Active Staff</b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="staff_suspend-tab" data-toggle="tab" href="#staff_suspend" role="tab" aria-controls="staff_suspend" aria-selected="false"><b style="color: red">{{$customer->where('role', '=', 'staff')->where('suspend', '=', 'yes')->get()->count()}} Suspended Staff</b></a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="admin2_active-tab" data-toggle="tab" href="#admin2_active" role="tab" aria-controls="admin2_active" aria-selected="false"><b style="color: green">{{$customer->where('role', '=', 'admin2')->where('suspend', '=', 'no')->get()->count()}} Active Admin Mini</b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="admin2_suspend-tab" data-toggle="tab" href="#admin2_suspend" role="tab" aria-controls="admin2_suspend" aria-selected="false"><b style="color: red">{{$customer->where('role', '=', 'admin2')->where('suspend', '=', 'yes')->get()->count()}} Suspended Admin Mini</b></a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="customer_active" role="tabpanel" aria-labelledby="customer_active-tab">

  @php
  $customer_active = $customer->where('role', '=', 'customer')->where('suspend', '=', 'no')->get();
  @endphp

  @if ($customer_active->count()>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
                <th>Customer Name</th>
                  <th>Customer Acc No</th>
                  <th>Savings Balance</th>
                  <th>Loan Balance</th>
                  <th>Date Joined</th>
                  <th>Actions</th>
                  
                  

                </tr>
              </thead>

              <tbody>
              @foreach ($customer_active as $customer)
                
                <tr>
                <td> {{$customer->name}} </td>
                  <td> {{$customer->username}} </td>
                  <td> {{$customer->savings_balance}}</td>
                  <td> {{$customer->loan_balance}} </td>
                  <td> {{$customer->created_at->format('d/m/Y H:i:s')}} </td>
                  <td> 
                  <button class="btn btn-success"><i class="fa fa-eye"></i><a href="/admin/customer/{{$customer->id}}">View</a></button>
                  <button class="btn btn-danger"><i class="fa fa-edit"></i><a href="/admin/customer/edit/{{$customer->id}}">Edit</a></button> 
                  <button class="btn btn-success"><i class="fa fa-pause"></i><a href="/admin/suspend/{{$customer->id}}"> Suspend</a></button>
                  </td>
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO RECORD FOUND
        </div>
        @endif
        </div>
  <div class="tab-pane fade" id="customer_suspend" role="tabpanel" aria-labelledby="customer_suspend-tab">

   @php
  $customer_suspend = $customer->where('role', '=', 'customer')->where('suspend', '=', 'yes')->get();
  @endphp

  @if ($customer_suspend->count()>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
                <th>Customer Name</th>
                  <th>Customer Acc No</th>
                  <th>Savings Balance</th>
                  <th>Loan Balance</th>
                  <th>Date Joined</th>
                  <th>Actions</th>
                  
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($customer_suspend as $customer)
               
                <tr>
               <td> {{$customer->name}} </td>
                  <td> {{$customer->username}} </td>
                  <td> {{$customer->savings_balance}}</td>
                  <td> {{$customer->loan_balance}} </td>
                  <td> {{$customer->created_at->format('d/m/Y H:i:s')}} </td>
                  <td> 
                  <button class="btn btn-success"><i class="fa fa-eye"></i><a href="/admin/customer/{{$customer->id}}">View</a></button>
                  <button class="btn btn-danger"><i class="fa fa-edit"></i><a href="/admin/customer/edit/{{$customer->id}}">Edit</a></button> 
                  <button class="btn btn-success"><i class="fa fa-play"></i><a href="/admin/unsuspend/{{$customer->id}}"> Unsuspend</a></button>
                  </td>
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO RECORD FOUND
        </div>
        @endif
        </div>
  <div class="tab-pane fade" id="staff_active" role="tabpanel" aria-labelledby="staff_active-tab">

  @php
  $staff_active = $customer->where('role', '=', 'staff')->where('suspend', '=', 'no')->get();
  @endphp


  @if ($staff_active->count()>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
               <th>Staff Name</th>
                  <th>staff Acc No</th>
                  <th>Date Added</th>
                  <th>Actions</th>
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($staff_active as $staff)
                <tr>
                <td> {{$staff->name}} </td>
                  <td> {{$staff->username}} </td>
                  <td> {{$staff->created_at->format('d/m/Y H:i:s')}} </td>
                  <td>
                  <button class="btn btn-success"><i class="fa fa-pause"></i><a href="/admin/suspend/{{$staff->id}}"> Suspend</a></button>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO RECORD FOUND
        </div>
        @endif
        </div>
<div class="tab-pane fade" id="staff_suspend" role="tabpanel" aria-labelledby="staff_suspend-tab">

  @php
  $staff_suspend = $customer->where('role', '=', 'staff')->where('suspend', '=', 'yes')->get();
  @endphp


  @if ($staff_suspend->count()>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
               <th>Staff Name</th>
                  <th>staff Acc No</th>
                  <th>Date Added</th>
                  <th>Actions</th>
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($staff_suspend as $staff)
                <tr>
                <td> {{$staff->name}} </td>
                  <td> {{$staff->username}} </td>
                  <td> {{$staff->created_at->format('d/m/Y H:i:s')}} </td>
                  <td>
                  <button class="btn btn-success"><i class="fa fa-play"></i><a href="/admin/unsuspend/{{$staff->id}}"> Unsuspend</a></button>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO RECORD FOUND
        </div>
        @endif
        </div>









        <div class="tab-pane fade" id="admin2_active" role="tabpanel" aria-labelledby="admin2_active-tab">

  @php
  $admin2_active = $customer->where('role', '=', 'admin2')->where('suspend', '=', 'no')->get();
  @endphp


  @if ($admin2_active->count()>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
               <th>Admin Mini Name</th>
                  <th>Admin Mini Acc No</th>
                  <th>Date Added</th>
                  <th>Actions</th>
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($admin2_active as $admin2)
                <tr>
                <td> {{$admin2->name}} </td>
                  <td> {{$admin2->username}} </td>
                  <td> {{$admin2->created_at->format('d/m/Y H:i:s')}} </td>
                  <td>
                  <button class="btn btn-success"><i class="fa fa-pause"></i><a href="/admin/suspend/{{$admin2->id}}"> Suspend</a></button>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO RECORD FOUND
        </div>
        @endif
        </div>
<div class="tab-pane fade" id="admin2_suspend" role="tabpanel" aria-labelledby="admin2_suspend-tab">

  @php
  $admin2_suspend = $customer->where('role', '=', 'admin2')->where('suspend', '=', 'yes')->get();
  @endphp


  @if ($admin2_suspend->count()>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
               <th>Admin Mini Name</th>
                  <th>Admin Mini Acc No</th>
                  <th>Date Added</th>
                  <th>Actions</th>
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($admin2_suspend as $admin2)
                <tr>
                <td> {{$admin2->name}} </td>
                  <td> {{$admin2->username}} </td>
                  <td> {{$admin2->created_at->format('d/m/Y H:i:s')}} </td>
                  <td>
                  <button class="btn btn-success"><i class="fa fa-play"></i><a href="/admin/unsuspend/{{$admin2->id}}"> Unsuspend</a></button>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO RECORD FOUND
        </div>
        @endif
        </div>

</div>

@endsection