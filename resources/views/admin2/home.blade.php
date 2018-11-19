@extends('layouts.app')
@php
ini_set('zlib.output_compression', 'Off');
ob_start();
$loan_count = 0;
@endphp

@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
@endsection

@section('title')
HONEYPAY | {{ Auth::user()->name }}
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

  <div class="row">

  <div class="col-md-4">
  <a href="/admin2/search"><button type="button" class="btn btn-primary btn-block p-5"><h3 class="text-center">Customers</h3></button></a>
  </div>

  <div class="col-md-4">
  <a href="/referal"><button type="button" class="btn btn-primary btn-block p-5"><h3 class="text-center">Referals</h3></button></a>
  </div>


  <div class="col-md-4">
  <a href="/stats/all"><button type="button" class="btn btn-primary btn-block p-5"><h3 class="text-center">Statistics</h3></button></a>
  </div>

  
</div>
  <br>

<div class="row">
  <div class="col-md-4">
  <a href="/verify/add"><button type="button" class="btn btn-primary btn-block p-5"><h3 class="text-center">Add Verification </h3></button></a>
  </div>


  <div class="col-md-4">
  <a href="/admin2/transaction"><button type="button" class="btn btn-primary btn-block p-5"><h3 class="text-center">Add Transaction</h3></button></a>
  </div>

  <div class="col-md-4">
  <a href="/admin2/newcustomer"><button type="button" class="btn btn-primary btn-block p-5"><h3 class="text-center">Add Customers</h3></button></a>
  </div>
</div>

<br>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="true"><b style="color: green">{{$approved->count()}} Approved </b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="false"><b style="color: blue">{{$pendings->count()}} Pending </b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false"><b style="color: red">{{$rejected->count()}} Rejected </b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="due-tab" data-toggle="tab" href="#due" role="tab" aria-controls="due" aria-selected="false"><b style="color: red">##loan_count## Loan Dues </b></a>
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
                  <th>Transaction Type</th>
                  <th>Recieved by</th>
                  <th>Transaction description</th>
                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Week Due</th>
                  <th>Applied Date</th>
                  <th>Verified Date</th>
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($approved as $approve)
              {{-- @if($approve->user->referal == Auth::user()->mentor) --}}
                @php
                $customer_approve = $customer->where('id','=',$approve->user_id)->first();
                $loan = $customer_approve->loan()->orderby('updated_at','desc')->first();
                $staff_approve = $customer->where('id','=',$approve->staff_id)->first();
                if (isset($loan) && $loan->veri_remark !='pending'){
                $latest_loan = $customer_approve->loan()->latest()->first();
                $week_due_date = $latest_loan->week_due_date;
                $due_date = $latest_loan->due_date;
                $skip_due = $latest_loan->skip_due;
                }
                
                @endphp
                <tr>
                <td> {{$approve->id}} </td>
                  <td> {{$customer_approve->username}} </td>
                  <td> {{$approve->type}} </td>
                  <td> {{$approve->recieved_by}} </td>
                  <td> {{$approve->description}} </td>
                  <td> @money($approve->debit) </td>
                  <td> @money($approve->credit) </td>
                  @if($approve->type == 'loan' && isset($loan) && $loan->veri_remark !='pending')
                  <td>{{$week_due_date->diffInWeeks($due_date, false) >= 0 ? $week_due_date->diffInWeeks($now, false) + $skip_due : '0'}}</td>
                  @else
                  <td> - </td>
                  @endif
                  <td> {{$approve->created_at->format('d/m/Y H:i:s')}} </td>
                  <td> {{$approve->updated_at->format('d/m/Y H:i:s')}} </td>
       
                </tr>
                {{-- @endif --}}
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
                @endphp
                <tr>
                <td> {{$pending->id}} </td>
                  <td> {{$customer_pending->username}} </td>
                  <td> {{$pending->type}} </td>
                  <td> {{$pending->recieved_by}} </td>
                  <td> {{$pending->description}} </td>
                  <td> @money($pending->debit) </td>
                  <td> @money($pending->credit) </td>
                  <td> {{$pending->created_at->format('d/m/Y H:i:s')}} </td>
                  <td><a href="/admin/aprove/{{$pending->id}}"><button class="btn btn-success"><i class="fa fa-eye"></i>Approve</button></a>
                  <a href="/admin/reject/{{$pending->id}}"><button class="btn btn-danger"><i class="fa fa-edit"></i>Reject</button></a></td>
                  
       
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
              {{-- @if($reject->user()->referal == Auth::user()->mentor) --}}
                @php
                $customer_reject = $customer->where('id','=',$reject->user_id)->first();
                @endphp
                <tr>
                <td> {{$reject->id}} </td>
                  <td> {{$customer_reject->username}} </td>
                  <td> {{$reject->type}} </td>
                  <td> {{$reject->recieved_by}} </td>
                  <td> {{$reject->description}} </td>
                  <td> @money($reject->debit) </td>
                  <td> @money($reject->credit) </td>
                  <td> {{$reject->created_at->format('d/m/Y H:i:s')}} </td>
                  <td> {{$reject->updated_at->format('d/m/Y H:i:s')}} </td>
       
                </tr>
               {{--  @endif --}}
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

  <div class="tab-pane fade" id="due" role="tabpanel" aria-labelledby="due-tab">
  @if ($dues->count()>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Customer Acc No</th>
                  <th>Due Date</th>
                  <th>Loan Category</th>
                  <th>Actions</th>
                  <th>Approved Date</th>
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($dues as $due)
                @php
                $customer_due = $customer->where('id','=',$due->user_id)->first();
                $loan = $loans->where('user_id', $due->user_id)->latest()->first();
                $condition = ($loan? $due->updated_at > $loan->updated_at: true);
                @endphp

                @if ($condition)
                {{-- @if($due->user()->referal == Auth::user()->mentor) --}}
                @php
                $loan_count += 1;
                @endphp 
                <tr>
                  <td> {{$customer_due->username}} </td>
                  <td> {{$due->due_date}} </td>
                  <td> {{$due->loan}} </td>
                  <td> 
                    <a href="/public/{{$due->form1}}"><button class="btn btn-primary
                    ">Form1</button></a>
                    <a href="/public/{{$due->form2}}"><button class="btn btn-primary
                    ">Form2</button></a>
                    <a href="/public/{{$due->form3}}"><button class="btn btn-primary
                    ">Form3</button></a>
                  </td>
                  <td> {{$due->updated_at->format('d/m/Y')}} </td>
       
                </tr>
                {{-- @endif --}}
                @endif
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

@php
echo str_replace('##loan_count##', $loan_count, ob_get_clean());
@endphp
@endsection
