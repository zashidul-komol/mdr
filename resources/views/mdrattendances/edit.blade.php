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
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="#"><h4 class="section-subtitle"><b>MDR Monthly Attendance</b></h4></a></li>
        </ul>
    </div>
</div>
    <div class="panel">
            <div class="panel-content">
                {{ Form::model(request()->old(),array('route' => array('mdrattendances.store'),'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}
                <div class="table-responsive">
                        <div class="form-group">
                          <label for="inputName" class="col-sm-1 ">Region</label>

                          <div class="col-sm-2">
                            {{Form::text('region_name',$RegionName,array('class' => 'form-control', 'readonly' => 'true'))}}
                              {!! $errors->first('region_id', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                          <label for="inputName" class="col-sm-1 ">Date</label>

                          <div class="col-sm-2">
                             {{Form::text('salary_date',$TodayDate, array('class' => 'form-control', 'readonly' => 'true'))}}
                              {!! $errors->first('salary_date', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                          <label for="inputName" class="col-sm-1 ">Month</label>

                          <div class="col-sm-2">
                             {{Form::text('month_name',$MonthName,array('class' => 'form-control' , 'readonly' => 'true'))}}
                              {!! $errors->first('month_id', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                          <label for="inputName" class="col-sm-1 ">Year</label>

                          <div class="col-sm-2">
                             {{Form::text('year',$year,array('class' => 'form-control' , 'readonly' => 'true'))}}
                              {!! $errors->first('year', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                      </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                        <thead>
                      <tr>
                        <th width="2%" align="left">SL</th>
                        <th width="12%" align="left">DB Name</th>
                        <th width="12%" align="left">MDR Name</th>
                        <th hidden="true">ID</th>
                        <th width="5%" align="left">M.Days</th>
                        <th width="5%" align="left">Leave</th>
                        <th width="5%" align="left">Unauth Leave</th>
                        <th width="7%" align="left">Extra Duty</th>
                        <th width="10%" align="left">Salary</th>
                        <th width="8%" align="left">TA</th>
                        <th width="8%" align="left">DA</th>
                        <th width="8%" align="left">Mobile</th>
                        <th width="10%" align="left">G.Salary</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php ($i=1)
                        @foreach ($MdrInformations as $data)
                      <tr>
                        <td>{{$i}}</td>
                        <td>{{$data->distributors->distributorName  ??  ''}}</td>
                        <td>{{$data->applicant_name  ??  ''}}</td>
                        <td hidden="true">{{Form::text('id[]',$data->id,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('working_days[]',$Month_Days,array('class' => 'form-control' , 'readonly' => 'true', 'id'=>'working_days'.$i))}}
                            {!! $errors->first('working_days[]', '<p class="text-danger">:message</p>' ) !!}</td>
                        <td><input align="center" style="width:70px;" type="text" name="authorized_leave[]" id="authorized_leave{{$i}}" class="rate"></td>
                        <td><input align="center" style="width:70px;" type="text" name="unauthorized_leave[]" id="unauthorized_leave{{$i}}" class="rate"></td>
                        <td><input align="center" style="width:70px;" type="text" name="extra_duty[]" id="extra_duty{{$i}}" class="rate" onblur="getAmount({{$i}})"></td>
                        <td>{{Form::text('salary[]',null,array('class' => 'form-control', 'readonly' => 'true', 'id'=>'salary'.$i))}}</td>
                        <td>{{Form::text('travelling_allowance[]',null,array('class' => 'form-control', 'readonly' => 'true', 'id'=>'travelling_allowance'.$i))}}</td>
                        <td>{{Form::text('dearness_allowance[]',null,array('class' => 'form-control', 'readonly' => 'true', 'id'=>'dearness_allowance'.$i))}}</td>
                        <td>{{Form::text('mobile_bill[]',null,array('class' => 'form-control', 'readonly' => 'true', 'id'=>'mobile_bill'.$i))}}</td>
                        <td>{{Form::text('gross_salary[]',null,array('class' => 'form-control', 'readonly' => 'true', 'id'=>'gross_salary'.$i))}}</td>
                      </tr>
                        @php ($i=$i+1)
                        @endforeach
                    </tbody>
                  </table>
                </div>
                <div align="center" class="table-responsive">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="submit" class="btn btn-primary">
                            PROPOSE FOR SALARY PAYMENT
                        </button>
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
    let authorized_leave_id = 'authorized_leave'+id
    let unauthorized_leave_id = 'unauthorized_leave'+id
    let extra_duty_id = 'extra_duty'+id
    
    var working_days = parseInt($('#'+working_day_id).val());
    var authorized_leave = parseInt($('#'+authorized_leave_id).val());
    var unauthorized_leave = parseInt($('#'+unauthorized_leave_id).val());
    var extra_duty = parseInt($('#'+extra_duty_id).val());
    alert(working_days);
    //alert('komol');
    if(working_days > 26){
        $('#travelling_allowance'+id).val(parseFloat(working_days*120));
        $('#dearness_allowance'+id).val(parseFloat(working_days*120));
        $('#mobile_bill'+id).val(parseFloat(500));
        $('#salary'+id).val(parseFloat(11000).toFixed(2));
        $('#gross_salary'+id).val(parseFloat((11000+500)+(120+120)*working_days).toFixed(2));
    }else{
       $('#travelling_allowance'+id).val(parseFloat(working_days*120));
        $('#dearness_allowance'+id).val(parseFloat(working_days*120));
        $('#mobile_bill'+id).val(parseFloat(500));
        $('#salary'+id).val(parseFloat(working_days*(11000/26)).toFixed(2));
        $('#gross_salary'+id).val(parseFloat((((11000/26)+500+120+120))*working_days).toFixed(2)); 
    }
    
 
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

