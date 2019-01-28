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

@section('script')
<script>
  function del(id){
    var del = confirm("Are u sure ?");
    if(del){
      /*var url = window.location.hostname+'/delete/'+id;
      var form = $('form action="'+url+'"method="post"></form>');
      $('body').append(form);
      form.submit();*/
      //alert(window.location.hostname+'/delete/'+id);
      window.location.replace('/delete/'+id);
    }
  }
  function role(id, username){
    //var
    $("#roles").attr("action", "/role/" + id);
    $(".modal-title").html("<span>Change Role for "+username+"</span>");
    //window.id =  id;

  }
</script>
@endsection

@section('menu')

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            
                <div class="card-header"><strong>SEARCH STAFF</strong></div>

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

                    <form class="form-horizontal" method="POST" action="/admin/searchstaff">
                        {{ csrf_field() }}
                       
                        <div class="form-group{{ $errors->has('search') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                <input id="search" type="text" class="form-control" name="search" value="{{session('search') ? session('search') : 'Search by Staff/Admin2 Account Number'}}" required>

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
                    </form>

                    @if ($_POST)
                    @if ($searchs->count()>0)
          <div class="table-responsive">
            <table class="display table table-bordered" id="" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Staff Acc No</th>
                  <th>Staff Name</th>
                  <th>Role</th>
                  <th>Actions</th>
                </tr>
              </thead>
              
              <tbody>
              @foreach ($searchs as $search)
                <tr>
                
                  <td> {{$search->username}} </td>
                  <td> {{$search->name}} </td>
                  <td> {{$search->role}} </td>
                  <td>
                  @if($search->suspend == 'no')
                  <button class="btn btn-success"><i class="fa fa-pause"></i><a href="/admin/suspend/{{$search->id}}">Suspend</a></button>
                  @else
                  <button class="btn btn-success"><i class="fa fa-play"></i><a href="/admin/unsuspend/{{$search->id}}">Unsuspend</a></button>
                  @endif
                   <button class="btn btn-danger" onclick="del({{$search->id}})">Delete</button>
                   <button class="btn btn-primary" data-toggle="modal" data-target="#role" onclick="role({{$search->id}}, {{$search->username}})">Change Role</button>
                  </td>

       
                </tr>
              @endforeach

              </tbody>
            </table>
            {{$searchs->links()}}
         </div>
         @else
         <div class="alert alert-danger">No Customer Found</div>
        @endif
        @endif

                   </div>
          </div>
        </div>
    </div>
</div>

@endsection
@section('modal')
<div class="modal fade" id="role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="" id="roles">
               @csrf
           {{--  <div class="modal-body mx-3">
                <div class="md-form mb-5">
                    <i class="fa fa-envelope prefix grey-text"></i>
                    <input type="username" id="defaultForm-email" class="form-control validate">
                    <label data-error="wrong" data-success="right" for="defaultForm-email">Account number</label>
                </div> --}}

                <div class="md-form mb-4">
                    <i class="fa fa-user prefix grey-text"></i>
                    <label data-error="wrong" data-success="right" for="defaultForm-pass">Roles</label>
                    <select name="role" id="defaultForm-pass" class="form-control validate">
                      <option value="admin">Admin</option>
                      <option value="admin2">Admin Mini</option>
                      <option value="staff">Staff</option>
                    </select>

                </div>

                <div class="modal-footer d-flex justify-content-center">
                <button class="btn btn-default">Submit</button>
            </div>

            </div>
            {{-- <div class="modal-footer d-flex justify-content-center">
                <button class="btn btn-default">Login</button>
            </div> --}}
        </div>
    </div>
</div>

{{-- <div class="text-center">
    <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalLoginForm">Launch Modal Login Form</a>
</div> --}}
@endsection