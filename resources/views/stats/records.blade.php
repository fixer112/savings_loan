@extends('layouts.app')
@section('css')
<link href="{{ asset('css/staff_style.css') }}" rel="stylesheet">
<link href="{{ asset('fonts/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
@endsection

@section('title')
HONEYPAYS | Statistic Records
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
             <div class="table-responsive">
               <table class="search table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                     

                   </tr>
                 </thead>
                
                 <tbody>
                 @foreach ($details as $detail)

                   <tr>
                   <td> {{$detail->id}} </td>
                     <td> {{$detail->type}} </td>
                     <td> {{$detail->recieved_by}} </td>
                     <td> {{$detail->description}} </td>
                     <td> @money($detail->debit) </td>
                     <td> @money($detail->credit) </td>
                     <td> {{$detail->created_at}} </td>
                     <td> {{$detail->updated_at}} </td>
            
                   </tr>
                 @endforeach

                 </tbody>
               </table>
            </div>
          </div>
        </div>
    </div>
</div>

@endsection