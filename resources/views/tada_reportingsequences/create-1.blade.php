@extends('layouts.admin')
@section('title', 'Add Reporting Sequece')
@section('content')
<!DOCTYPE html>
<html>
<head>
  <title>Add Remove Select Box Fields Dynamically using jQuery Ajax in PHP</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
<body>
  <div class="container">
     <br />
     <h3 align="center">Kabelliste 3.0</h3>
     <br />
     <div class="text-right">
       <a href="{{ url('/home') }}" class="btn btn-success mb-2">Home</a>
     </div>
     <h3 align="center">Testkabel</h3>
   <div class="table-responsive">
                <form method="post" id="cablelists">
                 <span id="result"></span>
                 <table class="table table-bordered table-striped" id="cablelists">
               <thead>
                 <tr>
                    <th>typeofmachine_id</th>
                    <th>cablename_id</th>
                    <th>lengh</th>
                    <th>Action</th>
                 </tr>
               </thead>
               <tbody>

               </tbody>
               <tfoot>
                <tr>
                <td colspan="3" align="right">&nbsp;</td>
                <td>
                  @csrf
                  <input type="submit" name="save" id="save" class="btn btn-primary" value="Save" />
                 </td>
                </tr>
               </tfoot>
           </table>
          </form>
   </div>
  </div>
@endsection
@component('common_pages.selectize')
    <script src="{{ asset('vendor/bootstrap_date-picker/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
         $('.datepicker').datepicker({ format: "yyyy-mm-dd",todayHighlight: true,autoclose:true});

    </script>
    <script>
$(document).ready(function(){

 var count = 1;

 dynamic_field(count);

 function dynamic_field(number)
 {
  html = '<tr>';
        html += '<td><input type="text" name="typeofmachine_id[]" class="form-control" /></td>';
         html += '<td><select class="form-control" id="cablename_id" name="cablename_id"><option value=""> -- Wähle einen Kabelnamen aus. --</option>@foreach($report_to as $cablename)<option value="{{$cablename->id}}">{{$cablename->name}}</option>@endforeach<span class="text-danger">{{ $errors->first('cablename_id') }}</span></select></td>';
        html += '<td><input type="text" name="lengh[]" class="form-control lengh" /></td>';
        
        if(number > 1)
        {
            html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove">Remove</button></td></tr>';
            $('tbody').append(html);
        }
        else
        {
            html += '<td><button type="button" name="add" id="add" class="btn btn-success">Add</button></td></tr>';
            $('tbody').html(html);
        }
 }

 $(document).on('click', '#add', function(){
  count++;
  dynamic_field(count);
 });

 $(document).on('click', '.remove', function(){
  count--;
  $(this).closest("tr").remove();
 });

 $('#dynamic_form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:'{{ route("reportingsequences.store") }}',
            method:'post',
            data:$(this).serialize(),
            dataType:'json',
            beforeSend:function(){
                $('#save').attr('disabled','disabled');
            },
            success:function(data)
            {
                if(data.error)
                {
                    var error_html = '';
                    for(var count = 0; count < data.error.length; count++)
                    {
                        error_html += '<p>'+data.error[count]+'</p>';
                    }
                    $('#result').html('<div class="alert alert-danger">'+error_html+'</div>');
                }
                else
                {
                    dynamic_field(1);
                    $('#result').html('<div class="alert alert-success">'+data.success+'</div>');
                }
                $('#save').attr('disabled', false);
            }
        })
      });

    });
    </script>
</body>
</html> 
    @slot('css')
     <!--Date picker-->
     <link rel="stylesheet" href="{{ asset('vendor/bootstrap_date-picker/css/bootstrap-datepicker3.min.css') }}">
    @endslot
@endcomponent


