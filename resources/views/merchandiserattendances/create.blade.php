@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="text-center">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="#"><h4 class="section-subtitle"><b>MDR Monthly Attendance2</b></h4></a></li>
        </ul>
    </div>
</div>
    <div class="panel">
            <div class="panel-content">
                {{ Form::model(request()->old(),array('route' => array('mdrattendances.store'),'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}
                <div class="table-responsive">
                        <div class="form-group">
                          <label for="inputName" class="col-sm-1 ">Depot</label>

                          <div class="col-sm-2">
                            {{Form::text('DepotName',$DepotName,array('class' => 'form-control', 'readonly' => 'true'))}}
                              {!! $errors->first('depot_id', '<p class="text-danger">:message</p>' ) !!}
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
                        <th width="8%" align="left">DB Name</th>
                        <th width="8%" align="left">MDR Name</th>
                        <th hidden="true">ID</th>
                        <th width="5%" align="left">Month Days</th>
                        <th width="3%" align="left">Leave</th>
                        <th width="3%" align="left">Absent</th>
                        <th width="3%" align="left">Weekly Holiday Duty</th>
                        <th width="3%" align="left">Govt Holiday Duty</th>
                        <th width="3%" align="left">EID Duty</th>
                        <th width="5%" align="left">Meeting Days</th>
                        <th width="6%" align="left">Working Days</th>
                        <th width="7%" align="left">Salary</th>
                        <th width="7%" align="left">W.Holiday Bill</th>
                        <th width="7%" align="left">G.Holiday Bill</th>
                        <th width="6%" align="left">EID Duty Bill</th>
                        <th width="5%" align="left">TA</th>
                        <th width="5%" align="left">DA</th>
                        <th width="6%" align="left">Mobile</th>
                        <th width="9%" align="left">G.Salary</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php ($i=1)
                        @foreach ($MdrInformations as $data)
                      <tr>
                        <td>{{$i}}</td>
                        <td>{{$data->distributors->distributorName or ''}}</td>
                        <td>{{$data->applicant_name or ''}}</td>
                        <td hidden="true">{{Form::text('id[]',$data->id,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td hidden="true">{{Form::text('basic_salary[]',$data->basic_salary,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td hidden="true">{{Form::text('Monthly_Total_Holidays[]',$Monthly_Total_Holidays,array('class' => 'form-control', 'readonly' => 'true', 'id'=>'Monthly_Total_Holidays'.$i))}}</td>
                        <td>{{Form::text('month_days[]',$Month_Days,array('class' => 'form-control' , 'readonly' => 'true', 'id'=>'month_days'.$i))}}
                            {!! $errors->first('month_days[]', '<p class="text-danger">:message</p>' ) !!}</td>
                        <td><input align="center" style="width:50px; background-color: #99ff99;" type="text" name="authorized_leave[]" id="authorized_leave{{$i}}" class="rate"></td>
                        <td><input align="center" style="width:50px; background-color: #99ff99;" type="text" name="unauthorized_leave[]" id="unauthorized_leave{{$i}}" class="rate"></td>
                        <td><input align="center" style="width:50px; background-color: #99ff99;" type="text" name="weekly_holiday[]" id="weekly_holiday{{$i}}" class="rate" ></td>
                        <td><input align="center" style="width:50px; background-color: #99ff99;" type="text" name="govt_holiday[]" id="govt_holiday{{$i}}" class="rate"></td>
                        <td><input align="center" style="width:50px; background-color: #99ff99;" type="text" name="eid_duty[]" id="eid_duty{{$i}}" class="rate" onblur="getWorkingDay({{$i}})"></td>
                        <td><input align="center" style="width:50px;" type="text" name="working_days[]" id="working_days{{$i}}" class="rate" onblur="getAmount({{$i}})" readonly="true"></td>
                        <td><input align="center" style="width:70px;" type="text" name="salary[]" id="salary{{$i}}" class="rate" readonly="true"></td>
                        <td><input align="center" style="width:60px;" type="text" name="weekly_holiday_bill[]" id="weekly_holiday_bill{{$i}}" class="rate" readonly="true"></td>
                        <td><input align="center" style="width:60px;" type="text" name="govt_holiday_bill[]" id="govt_holiday_bill{{$i}}" class="rate" readonly="true"></td>
                        <td><input align="center" style="width:60px;" type="text" name="eid_duty_bill[]" id="eid_duty_bill{{$i}}" class="rate" readonly="true"></td>
                        <td><input align="center" style="width:40px;" type="text" name="travelling_allowance[]" id="travelling_allowance{{$i}}" class="rate" readonly="true"></td>
                        <td><input align="center" style="width:40px;" type="text" name="dearness_allowance[]" id="dearness_allowance{{$i}}" class="rate" readonly="true"></td>
                        <td><input align="center" style="width:40px;" type="text" name="mobile_bill[]" id="mobile_bill{{$i}}" class="rate" readonly="true"></td>
                        <td><input align="center" style="width:70px;" type="text" name="gross_salary[]" id="gross_salary{{$i}}" class="rate" readonly="true"></td>
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
    let weekly_holiday_id = 'weekly_holiday'+id
    let govt_holiday_id = 'govt_holiday'+id
    let eid_duty_id = 'eid_duty'+id
    let month_days_id = 'month_days'+id
    let basic_salary_id = 'basic_salary'+id
    
    var working_days = parseInt($('#'+working_day_id).val());
    var authorized_leave = parseInt($('#'+authorized_leave_id).val());
    var unauthorized_leave = parseInt($('#'+unauthorized_leave_id).val());
    var weekly_holiday = parseInt($('#'+weekly_holiday_id).val());
    var govt_holiday = parseInt($('#'+govt_holiday_id).val());
    var eid_duty = parseInt($('#'+eid_duty_id).val());
    var month_days = parseInt($('#'+month_days_id).val());
    var basic_salary = parseFloat($('#'+basic_salary_id).val());
    alert(working_days);
    //alert('komol');
    
    $('#travelling_allowance'+id).val(parseFloat(working_days*120));
    $('#dearness_allowance'+id).val(parseFloat(working_days*120));
    
    $('#mobile_bill'+id).val(parseFloat(500));
    $('#salary'+id).val(parseFloat((basic_salary)-((basic_salary/month_days)*unauthorized_leave)).toFixed(0));
    $('#weekly_holiday_bill'+id).val(parseFloat((basic_salary/30)*weekly_holiday).toFixed(0));
    $('#govt_holiday_bill'+id).val(parseFloat((basic_salary/30)*govt_holiday).toFixed(0));
    $('#eid_duty_bill'+id).val(parseFloat(((basic_salary/30)*2)*eid_duty).toFixed(0));
    $('#gross_salary'+id).val(parseFloat(((basic_salary+500)+(120+120)*working_days)+((basic_salary/30)*(weekly_holiday + govt_holiday)) -((basic_salary/month_days)*unauthorized_leave) + ((basic_salary/30)*2)*eid_duty).toFixed(0));
       
 }

 function getWorkingDay(id){
    let Monthly_Total_Holidays_id = 'Monthly_Total_Holidays'+id
    let month_days_id = 'month_days'+id
    let authorized_leave_id = 'authorized_leave'+id
    let unauthorized_leave_id = 'unauthorized_leave'+id
    let weekly_holiday_id = 'weekly_holiday'+id
    let govt_holiday_id = 'govt_holiday'+id
    let eid_duty_id = 'eid_duty'+id
    
    var Monthly_Total_Holidays = parseInt($('#'+Monthly_Total_Holidays_id).val());
    var month_days = parseInt($('#'+month_days_id).val());
    var authorized_leave = parseInt($('#'+authorized_leave_id).val());
    var unauthorized_leave = parseInt($('#'+unauthorized_leave_id).val());
    var weekly_holiday = parseInt($('#'+weekly_holiday_id).val());
    var govt_holiday = parseInt($('#'+govt_holiday_id).val());
    var eid_duty = parseInt($('#'+eid_duty_id).val());
    alert(working_days);
    //alert('komol');
    
    $('#working_days'+id).val(parseFloat((month_days) - (Monthly_Total_Holidays + authorized_leave + unauthorized_leave) + weekly_holiday + govt_holiday + eid_duty));
  
    
 }
</script>
    @slot('css')
     <!--Date picker-->
     <link rel="stylesheet" href="{{ asset('vendor/bootstrap_date-picker/css/bootstrap-datepicker3.min.css') }}">
    @endslot
@endcomponent

