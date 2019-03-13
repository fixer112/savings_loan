@extends('layouts.app')
@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
<style type="text/css">
.img img {
    width: 16%;
    float: right;
}
</style>
@endsection

@section('title')
HONEYPAYS | View Customer- {{$user->username}}
@endsection

@section('menu')

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><strong>Customer Dashboard</strong></div>

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

                        
                            Savings Account Balance : <strong>&#8358 {{ $user->savings_balance }}</strong>
                            <p>Loan Balance : <strong>&#8358 {{ $user->loan_balance }}</strong></p><hr>

                            <div class="img"><img src="/{{$user->passport}}"></div>

                            <p>Name: {{ $user->name }}</p>
                            <p>Account Number: {{ $user->username }}</p>

                        
                            <p>Residetial Address: {{ $user->resi_add }} </p>

                            <p>Business Address: {{ $user->busi_add }} </p>

                            <p>Nature of business: {{ $user->nature_add }} </p>

                            <p>Phone Number: {{ $user->number }} </p>

                            @if (isset($loan) && $loan->veri_remark !='pending')


                            <p>Loan Application Date: {{ $loan->created_at->format('d/m/Y') }} </p>

                            <p>Loan Due Date: {{ $loan->due_date->format('d/m/Y') }} </p>
                            @php
                            $week_due_date = $latest_loan->week_due_date;
                            $due_date = $latest_loan->due_date;
                            $skip_due = $latest_loan->skip_due;

                            @endphp

                            <p>Week Due Count: <strong>{{$week_due_date->diffInWeeks($due_date, false) >= 0 ? $week_due_date->diffInWeeks($now, false) + $skip_due : '0'}}</strong>
                            @if($latest_loan->due != 'paid')
                            <a href="/admin/fee/{{$latest_loan->id}}"><button class="btn btn-success">Verify Due</button></a>
                            @else
                            <strong style="color: green"> (DUE PAID)</strong>
                           @endif
                            </p>

                            <p>
                            @if($latest_loan->interest_status !='paid')

                            Interest Status: <strong style="color: red" >{{ $latest_loan->interest_status }} </strong>
                            <a href="/admin/veri_interest/{{$latest_loan->id}}"><button class="btn btn-success">Verify Interest</button></a>

                            @else

                            Interest Status: <strong style="color: green" >{{ $latest_loan->interest_status }} </strong>

                            @endif
                            </p>

                            <p>Loan Category: {{ $loan->loan_category }} </p>

                            <p >Verification Remark: <strong style="color: {{ $loan->veri_remark =='Approved' ? 'green' : 'red' }}" >{{ $loan->veri_remark }} </strong></p>
                            @else
                            <p><strong style="color:red"> No loan history yet</strong></p>
                            @endif

                            <p><button class="btn btn-info"><i class="fa fa-eye"></i><a href="/admin/customer/collateral/{{$user->id}}">view Collaterals</a></button> <button class="btn btn-info"><i class="fa fa-eye"></i><a href="/{{$user->idcard}}">view IdCard</a></button></p>

                            <p>@if($user->suspend == 'no')
                  <button class="btn btn-success"><i class="fa fa-pause"></i><a href="/admin/suspend/{{$user->id}}">Suspend</a></button>
                  @else
                  <button class="btn btn-success"><i class="fa fa-play"></i><a href="/admin/unsuspend/{{$user->id}}">Unsuspend</a></button>
                  @endif
                   <button class="btn btn-danger"><i class="fa fa-edit"></i><a href="/admin/customer/edit/{{$user->id}}">Edit</a></button>
                  </p>

                        
                    </div>
                    </div>
                    
        <div class="card-header history">
        TRANSACTION HISTORY
        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="approved-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="true"><b style="color: green">{{$historys->total()}} Approved</b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false"><b style="color: red">{{$rejected->total()}} Rejected</b></a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="approved" role="tabpanel" aria-labelledby="approved-tab">
  @if ($historys->count()>0)
          <div class="table-responsive">
            <table class="display table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                <th>Transaction ID</th>
                  <th>Transaction Type</th>
                  <th>Recieved by</th>
                  <th>Transaction description</th>
                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Applied Date</th>
                  <th>Verified Date</th>
                  <th>Actions</th>
                  

                </tr>
              </thead>
             
              <tbody>
              @foreach ($historys as $history)

                <tr>
                <td> {{$history->id}} </td>
                  <td> {{$history->type}} </td>
                  <td> {{$history->recieved_by}} </td>
                  <td> {{$history->description}} </td>
                  <td> {{$history->debit}} </td>
                  <td> {{$history->credit}} </td>
                  <td> {{$history->created_at->format('d/m/Y H:i:s')}} </td>
                  <td> {{$history->updated_at->format('d/m/Y H:i:s')}} </td>
                  <td><a href="/reject/{{$history->id}}"><button class="btn btn-danger">Reject</button></a></td>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            {{$historys->links()}}
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO Transaction Found
        </div>
        @endif
        </div>
  <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
  @if ($rejected->count()>0)
          <div class="table-responsive">
            <table class="display table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                <th>Transaction ID</th>
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

                <tr>
                <td> {{$reject->id}} </td>
                  <td> {{$reject->type}} </td>
                  <td> {{$reject->recieved_by}} </td>
                  <td> {{$reject->description}} </td>
                  <td> {{$reject->debit}} </td>
                  <td> {{$reject->credit}} </td>
                  <td> {{$reject->created_at->format('d/m/Y H:i:s')}} </td>
                  <td> {{$reject->updated_at->format('d/m/Y H:i:s')}} </td>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            {{$rejected->links()}}
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO Transaction Found
        </div>
        @endif</div>
</div>
        

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
