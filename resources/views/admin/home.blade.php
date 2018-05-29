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

                    <div style="padding-bottom: 10px">

                    <a href="/admin/info"><button class="btn btn-info">{{$customer->where('role', '=', 'customer')->get()->count()}} Customer</button></a> 

                    <a href="/admin/info"><button class="btn btn-info">{{$customer->where('role', '=', 'staff')->get()->count()}} Staff</button></a>

                    <a href="/admin/info"><button class="btn btn-info">{{$customer->where('role', '=', 'admin2')->get()->count()}} Admin Mini</button></a>



                    </div>

            <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="true"><b style="color: green">{{$approved->count()}} Approved</b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="false"><b style="color: blue">{{$pendings->count()}} Pending </b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false"><b style="color: red">{{$rejected->count()}} Rejected </b></a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="approved" role="tabpanel" aria-labelledby="approved-tab">
  @if ($approved->count()>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
                <th>Transaction ID</th>
                  <th>Customer Acc No</th>
                  <th>Staff Acc No</th>
                  <th>Transaction Type</th>
                  <th>Recieved by</th>
                  <th>Transaction description</th>
                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Applied Date</th>
                  <th>Verified Date</th>
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($approved as $approve)
                @php
                $customer_approve = $customer->where('id','=',$approve->user_id)->first();
                $staff_approve = $customer->where('id','=',$approve->staff_id)->first();
                @endphp
                <tr>
                <td> {{$approve->id}} </td>
                  <td> {{$customer_approve->username}} </td>
                  <td> {{$staff_approve ?  $staff_approve->username : 'none'}}</td>
                  <td> {{$approve->type}} </td>
                  <td> {{$approve->recieved_by}} </td>
                  <td> {{$approve->description}} </td>
                  <td> {{$approve->debit}} </td>
                  <td> {{$approve->credit}} </td>
                  <td> {{$approve->created_at->format('d/m/Y H:i:s')}} </td>
                  <td> {{$approve->updated_at->format('d/m/Y H:i:s')}} </td>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO Approved Transaction Found
        </div>
        @endif
        </div>
  <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
  @if ($pendings->count()>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
                <th>Transaction ID</th>
                  <th>Customer Acc No</th>
                  <th>Staff Acc No</th>
                  <th>Transaction Type</th>
                  <th>Recieved by</th>
                  <th>Transaction description</th>
                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Applied Date</th>
                  <th>Actions</th>
                  
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($pendings as $pending)
                @php
                $customer_pending = $customer->where('id','=',$pending->user_id)->first();
                $staff_pending = $customer->where('id','=',$pending->staff_id)->first();
                @endphp
                <tr>
                <td> {{$pending->id}} </td>
                  <td> {{$customer_pending->username}} </td>
                  <td> {{$staff_pending ?  $staff_pending->username : 'none'}}</td>
                  <td> {{$pending->type}} </td>
                  <td> {{$pending->recieved_by}} </td>
                  <td> {{$pending->description}} </td>
                  <td> {{$pending->debit}} </td>
                  <td> {{$pending->credit}} </td>
                  <td> {{$pending->created_at->format('d/m/Y')}} </td>
                  <td><button class="btn btn-success"><i class="fa fa-eye"></i><a href="/admin/aprove/{{$pending->id}}">Approve</a></button>
                  <button class="btn btn-danger"><i class="fa fa-edit"></i><a href="/admin/reject/{{$pending->id}}">Reject</a></button></td>
                  
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO Pending Transaction Found
        </div>
        @endif
        </div>
  <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
  @if ($rejected->count()>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
                <th>Transaction ID</th>
                  <th>Customer Acc No</th>
                  <th>Staff Acc No</th>
                  <th>Transaction Type</th>
                  <th>Recieved by</th>
                  <th>Transaction description</th>
                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Applied Date</th>
                  <th>Rejected Date</th>
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($rejected as $reject)
                @php
                $customer_reject = $customer->where('id','=',$reject->user_id)->first();
                $staff_reject = $customer->where('id','=',$reject->user_id)->first();
                @endphp
                <tr>
                <td> {{$reject->id}} </td>
                  <td> {{$customer_reject->username}} </td>
                  <td> {{$staff_reject ?  $staff_reject->username : 'none'}} </td>
                  <td> {{$reject->type}} </td>
                  <td> {{$reject->recieved_by}} </td>
                  <td> {{$reject->description}} </td>
                  <td> {{$reject->debit}} </td>
                  <td> {{$reject->credit}} </td>
                  <td> {{$reject->created_at->format('d/m/Y')}} </td>
                  <td> {{$reject->updated_at->format('d/m/Y')}} </td>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO Rejected Transaction Found
        </div>
        @endif
        </div>
</div>
@endsection