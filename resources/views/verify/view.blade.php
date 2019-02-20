@extends('layouts.app')
@php
$id = Auth::user()->id;
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
            
                <div class="card-header"><strong>Verifications</strong></div>

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
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="true"><b style="color: green">{{count($approved)}} Approved </b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="false"><b style="color: blue">{{count($pendings)}} Pending </b></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false"><b style="color: red">{{count($rejected)}} Rejected </b></a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="approved" role="tabpanel" aria-labelledby="approved-tab">
  @if (count($approved)>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
                <th>Due Date</th>
                  <th>Loan Category</th>
                  <th>Customer Account Number </th>
                  <th>Actions</th>
                  <th>Applied Date</th>
                  <th>Approved Date</th>
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($approved as $approve)
                @php
                $customer_approve = $customer->where('id','=',$approve->user_id)->first();
                @endphp
                <tr>
                <td> {{$approve->due_date}} </td>
                  <td> {{$approve->loan}} </td>
                  <td> {{$customer_approve->username}} </td>
                  <td>
                    <a href="/public/{{$approve->form1}}"><button class="btn btn-primary
                    ">Form1</button></a>
                    <a href="/public/{{$approve->form2}}"><button class="btn btn-primary
                    ">Form2</button></a>
                    <a href="/public/{{$approve->form3}}"><button class="btn btn-primary
                    ">Form3</button></a>
                    @if (!empty($approve->form4))
                    <a href="/public/{{$approve->form4}}"><button class="btn btn-primary
                    ">Form4</button></a>
                    @endif
                  </td>
                  <td> {{$approve->created_at->format('d/m/Y H:i:s')}} </td>
                  <td> {{$approve->updated_at->format('d/m/Y H:i:s')}} </td>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO Approved Verification Found
        </div>
        @endif
        </div>
  <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
  @if (count($pendings)>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Loan Category</th>
                  <th>Customer Account Number </th>
                  <th>Action</th>
                  <th>Applied Date</th>
                  
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($pendings as $pending)
                @php
                $customer_pending = $customer->where('id','=',$pending->user_id)->first();
                $id = $pending->id;
                @endphp
                <tr>
                <td> {{$pending->loan}} </td>
                  <td> {{$customer_pending->username}} </td>
                  <td>
                    <a href="/public/{{$pending->form1}}"><button class="btn btn-primary
                    ">Form1</button></a>
                    <a href="/public/{{$pending->form2}}"><button class="btn btn-primary
                    ">Form2</button></a>
                    <a href="/public/{{$pending->form3}}"><button class="btn btn-primary
                    ">Form3</button></a>
                     @if (!empty($pending->form4))
                    <a href="/public/{{$pending->form4}}"><button class="btn btn-primary
                    ">Form4</button></a>
                    @endif
                    @if(Auth::user()->role == 'admin')
                    <a href="#approve" aria-expanded="false" data-toggle="modal"><button onclick="actionapprove(document.getElementById('formapprove'), {{$pending->id}});" class="btn btn-success
                    ">Approve</button></a>
                    <a href="#reject" aria-expanded="false" data-toggle="modal"><button onclick="actionreject(document.getElementById('formreject'), {{$pending->id}});" class="btn btn-danger
                    ">Reject</button></a>
                    @endif
                  </td>
                  <td> {{$pending->created_at->format('d/m/Y H:i:s')}} </td>
                  
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO Pending Verification Found
        </div>
        @endif
        </div>
  <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
  @if (count($rejected)>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
                <th>Loan Category</th>
                  <th>Customer Account Number </th>
                  <th>Reject Reason</th>
                  <th>Actions</th>
                  <th>Applied Date</th>
                  <th>Rejected Date</th>
                  

                </tr>
              </thead>
              
              <tbody>
              @foreach ($rejected as $reject)
                @php
                $customer_reject = $customer->where('id','=',$reject->user_id)->first();
                @endphp
                <tr>
                <td> {{$reject->loan}} </td>
                  <td> {{$customer_reject->username}} </td>
                  <td>{{$reject->reason}}</td>
                  <td>
                    <a href="/public/{{$reject->form1}}"><button class="btn btn-primary
                    ">Form1</button></a>
                    <a href="/public/{{$reject->form2}}"><button class="btn btn-primary
                    ">Form2</button></a>
                    <a href="/public/{{$reject->form3}}"><button class="btn btn-primary
                    ">Form3</button></a>
                     @if (!empty($reject->form4))
                    <a href="/public/{{$reject->form4}}"><button class="btn btn-primary
                    ">Form4</button></a>
                    @endif
                  </td>
                  <td> {{$reject->created_at->format('d/m/Y H:i:s')}} </td>
                  <td> {{$reject->updated_at->format('d/m/Y H:i:s')}} </td>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
         </div>
        
        @else
       <div class="alert alert-danger error">
        NO Rejected Verification Found
        </div>
        @endif
        </div>
</div>
@endsection

@section('modal')
         <!-- The Modal Reject -->
<div class="modal" id="reject">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
            <div class="icon-box">
                    <i class="material-icons fa fa-ban"></i>
                </div>
        <h4 class="modal-title">Are you sure?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <!-- Modal body -->
      <div class="modal-body">
      <form method="GET" id="formreject" action="">
      @csrf
            <p>Do you really want to reject this verification?</p>

            <div class="form-group {{$errors->has('reason') ? 'invalid-feedback' : ''}}">
            <input type="text" id="reason" name="reason" class="form-control" placeholder ="Reason" required>
            @if ($errors->has('reason'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('reason') }}</strong>
        </span>
    @endif
        </div>

        <button id="t" type="submit" class="btn btn-danger">Reject</button>

      </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- The Modal Reject -->
<div class="modal" id="approve">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
            <div class="icon-box">
                    <i class="material-icons fa fa-ban"></i>
                </div>
        <h4 class="modal-title">Are you sure?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <!-- Modal body -->
      <div class="modal-body">
      <form method="GET" id="formapprove" action="">
      @csrf
            <p>Do you really want to approve this verification?</p>

            <div class="form-group {{$errors->has('due_date') ? 'invalid-feedback' : ''}}">
              Due Date
            <input type="date" id="due_date" name="due_date" class="form-control" placeholder ="Due Date" required>
            @if ($errors->has('due_date'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('due_date') }}</strong>
        </span>
    @endif
        </div>

        <button id="t" type="submit" class="btn btn-success">Approve</button>

      </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<script>
        function actionreject(form, id){
          var a = '/verify/reject/'+id;
          console.log(a);
          form.action = '/verify/reject/'+id;
        }

        function actionapprove(form,id){
          var a = '/verify/approve/'+id;
          console.log(a);
          form.action = '/verify/approve/'+id;
        }
      </script>
@endsection