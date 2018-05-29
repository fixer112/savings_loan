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
                  </td>
       
                </tr>
              @endforeach

              </tbody>
            </table>
            
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