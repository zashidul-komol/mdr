@extends('layouts.admin')

<style type="text/css">
  input:valid {
    background-color: #99ff99;
}
select:valid {
    background-color: #99ff99;
}
textarea:valid {
    background-color: #99ff99;
}

</style>
@section('content')
<div class="content-header">
    <div class="text-center">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="#"><h4 class="section-subtitle"><b>MDR Monthly Attendance Report Download</b></h4></a></li>
        </ul>
    </div>
</div>
    <div class="panel">
        
            <div class="panel-content">
               {{ Form::model(request()->old(),array('route' => array('mdrattendances.downloadAttendanceReport'),'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}
                <div class="table-responsive">
                        <div class="form-group">
                          {{Form::label('Depots:',null,array('class' => 'control-label col-sm-1'))}}
                            <div class="col-md-8">
                                 {{Form::select('depot_id[]',$depots->toArray(),$depots,array('class'=> 'form-control select2', 'id'=>'depot_id','multiple'=>true))}}
                                
                            </div>
                        </div>
                        <div class="form-group">
                          {{Form::label('Status:',null,array('class' => 'control-label col-sm-1'))}}
                            <div class="col-md-8">
                                 {{Form::select('status',[''=>'--Please Select Status--']+['active'=>'Active', 'inactive'=>'Inactive'],null,array('class' => 'form-control'))}}
                                
                            </div>
                        </div>
                                    
                          
                          <label for="inputName" class="col-sm-1 ">Date</label>

                          <div class="col-sm-2">
                             {{Form::text('salary_date',$TodayDate, array('class' => 'form-control', 'readonly' => 'true'))}}
                              {!! $errors->first('salary_date', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                          <label for="inputName" class="col-sm-1 ">Month</label>

                          <div class="col-sm-2">
                             {{Form::select('month_id',[''=>'--Please Select Month--']+$Months->toArray(),null,array('class' => 'form-control'))}}
                              {!! $errors->first('month_id', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                          <label for="inputName" class="col-sm-1 ">Year</label>

                          <div class="col-sm-2">
                             {{Form::select('year',[''=>'--Please Select Year--']+$Years->toArray(),null,array('class' => 'form-control'))}}
                              {!! $errors->first('year', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                          
                      </div>
                      <div class="form-group">
                            <div align="center" class="col-md-8 col-md-offset-2">
                                <button type="submit" class="btn btn-primary" value="2" id="attendance" name="Attendance">
                                    Salary Sheet
                                </button>
                                <button type="submit" class="btn btn-primary" value="4" id="attendance" name="Attendance">
                                    TA/DA Bill
                                </button>
                                
                            </div>
                        </div>
                </div>
                {{ Form::close() }} 
                
            </div>
            
            
    </div>

    
@endsection

@component('common_pages.selectize')
@include('common_pages.max_length')
<script src="{{ asset('vendor/bootstrap_date-picker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">

        $('.datepicker').datepicker({ format: "yyyy-mm-dd",todayHighlight: true,autoclose:true});

        //get shops or distributor
        function getExecutiveDepotShop(depotId){
          $('#shop-list').html('');
          $.ajax({
              type: 'Get',
              url:"{{ route('ajax.getShops') }}",
              data:{depot_id:depotId,distributor:1}
            }) .done(function(response) {
             $('#shop-list').html(response);
           //Select2 basic example
             $.fn.select2.defaults.set( "theme", "bootstrap" );
              $(".select2").select2({
                 // placeholder: function(){
                 //     $(this).data('placeholder');
                 // },
                 allowClear: true
             });
            if('{{old('shop_id')}}'){
              $("#shop_id").val('{{old('shop_id')}}').change();
            }

          })
          .fail(function(response) {
          });
        }

            $('#submit').prop("disabled", true);
            $('input:checkbox').click(function() {
             if ($(this).is(':checked')) {
             $('#submit').prop("disabled", false);
             } else {
             if ($('.checks').filter(':checked').length < 1){
             $('#submit').attr('disabled',true);}
             }
            });
    </script>

 <script>
  function getAmount(id){
    let working_day_id = 'working_days'+id
    var working_days = parseInt($('#'+working_day_id).val());
    //alert(working_days);
    //alert('komol');
    $('#travelling_allowance'+id).val(parseFloat(working_days*120).toFixed(2));
    $('#dearness_allowance'+id).val(parseFloat(working_days*120).toFixed(2));
    $('#mobile_bill'+id).val(parseFloat(working_days*(500/26)).toFixed(2));
    $('#salary'+id).val(parseFloat(working_days*(10000/26)).toFixed(2));
    $('#gross_salary'+id).val(parseFloat((((10000/26)+(500/26)+120+120))*working_days).toFixed(2));
 
    var value2 = $('#value2').val();
    var percent2 = $('#rate2').val();
    $('#amount2').val(value2*percent2/100);
 
     //get the sum of each column of each row
  var sum_value = 0;
  $('.value').each(function(){
    sum_value += +$(this).val();
    $('#total_value').val(sum_value);
  })
 
  var sum_rate = 0;
  $('.rate').each(function(){
    sum_rate += +$(this).val();
    $('#total_rate').val(sum_rate);
  })
 
  var sum_amount = 0;
  $('.amount').each(function(){
    sum_amount += +$(this).val();
    $('#total_amount').val(sum_amount);
  })
 
  
  }
</script>
    @slot('css')
     <!--Date picker-->
     <link rel="stylesheet" href="{{ asset('vendor/bootstrap_date-picker/css/bootstrap-datepicker3.min.css') }}">
    @endslot
@endcomponent

