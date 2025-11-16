@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="text-center">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="#"><h4 class="section-subtitle"><b>Merchandiser Monthly Attendance</b></h4></a></li>
        </ul>
    </div>
</div>
    <div class="panel">
            <div class="panel-content">
                {{ Form::model(request()->old(),array('route' => array('merchandiserattendances.storeMerchandiser'),'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}
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
                        <th width="8%" align="left">Merchandiser Name</th>
                        <th hidden="true">ID</th>
                        <th width="3%" align="left">Month Days</th>
                        <th width="3%" align="left">Leave</th>
                        <th width="3%" align="left">Unauthorize Leave</th>
                        <th width="3%" align="left">Weekly Holiday Duty</th>
                        <th width="3%" align="left">Govt Holiday Duty</th>
                        <th width="3%" align="left">Meeting Days</th> 
                        <th width="3%" align="left">Others TA</th>                       
                        <th width="4%" align="left">EID Duty</th>
                        <th width="6%" align="left">Working Days</th>
                        <th width="5%" align="left">Payable Days</th>
                        <th width="7%" align="left">Salary</th>
                        <th width="6%" align="left">W.Holiday Bill</th>
                        <th width="6%" align="left">G.Holiday Bill</th>
                        <th width="6%" align="left">EID Duty Bill</th>
                        <th width="5%" align="left">TA</th>
                        <th width="4%" align="left">DA</th>
                        <th width="4%" align="left">Mobile</th>
                        <th width="8%" align="left">G.Salary</th>
                      </tr>
                    </thead>
                    <tbody>

                        @php ($i=1)
                        @foreach ($MdrInformations as $data)
                      <tr>
                        <td>{{$i}}</td>
                        <td>{{$data->applicant_name  ??  ''}}</td>
                        <td hidden="true">{{Form::text('id[]',$data->id,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td hidden="true">{{Form::text('basic_salary[]',$data->basic_salary,array('class' => 'form-control', 'readonly' => 'true', 'id'=>'$data->basic_salary'.$i))}}</td>
                        <td hidden="true">{{Form::text('effectivedate[]',$data->effectivedate,array('class' => 'form-control', 'readonly' => 'true', 'id'=>'$data->effectivedate'.$i))}}</td>
                        <td hidden="true">{{Form::text('Monthly_Total_Holidays[]',$Monthly_Total_Holidays,array('class' => 'form-control', 'readonly' => 'true', 'id'=>'Monthly_Total_Holidays'.$i))}}</td>
                        <td>{{Form::text('month_days[]',$Month_Days,array('class' => 'form-control' , 'readonly' => 'true', 'id'=>'month_days'.$i))}}
                        {!! $errors->first('month_days[]', '<p class="text-danger">:message</p>' ) !!}</td>
                        <td><input align="center" style="width:60px; background-color: #99ff99;" type="text" name="authorized_leave[]" id="authorized_leave{{$i}}" class="rate"></td>
                        <td><input align="center" style="width:60px; background-color: #99ff99;" type="text" name="unauthorized_leave[]" id="unauthorized_leave{{$i}}" class="rate"></td>
                        <td><input align="center" style="width:50px; background-color: #99ff99;" type="text" name="weekly_holiday[]" id="weekly_holiday{{$i}}" class="rate" ></td>
                        <td><input align="center" style="width:50px; background-color: #99ff99;" type="text" name="govt_holiday[]" id="govt_holiday{{$i}}" class="rate"></td>
                        <td><input align="center" style="width:50px; background-color: #99ff99;" type="text" name="meeting_days[]" id="meeting_days{{$i}}" class="rate"></td>
                        <td><input align="center" style="width:50px; background-color: #99ff99;" type="text" name="others_ta_bill[]" id="others_ta_bill{{$i}}" class="rate"></td>
                        <td><input align="center" style="width:50px; background-color: #99ff99;" type="text" name="eid_duty[]" id="eid_duty{{$i}}" class="rate" onblur="getWorkingDay({{$i}},'{{$data->effectivedate}}')"></td>
                        <td><input align="center" style="width:50px;" type="text" name="working_days[]" id="working_days{{$i}}" class="rate" onblur="getAmount({{$i}},'{{$data->effectivedate}}','{{$data->basic_salary}}')" readonly="true"></td>
                        <td><input align="center" style="width:40px;" type="text" name="payable_days[]" id="payable_days{{$i}}" class="rate" readonly="true"></td>
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
    const MONTHNAMES = ["January", "February", "March", 
                "April", "May", "June",
                "July", "August", "September", 
                "October", "November", "December"
              ];
    const DAYNAMES = ["Sunday", "Monday", "Tuesday", "Wednesday", 
                "Thursday", "Friday", "Saterday"
              ];


    function getEffectiveDate(effectivedate){

        let employerEffectiveDate = effectivedate;
        let employerJoinDate = new Date(employerEffectiveDate);
        const parts = employerEffectiveDate.split(/[- :]/);
        var month = parts[1];
        var year = parts[0];

        var currentdate = new Date();
        var cur_month = currentdate.getMonth() + 1;
        var cur_year = currentdate.getFullYear();

        //alert(employerEffectiveDate);

        if (parseInt(cur_month) == parseInt(month) && year == cur_year) {
            let current_month_first_day = cur_year+'-'+cur_month+'-01';
            let currentMonthEffectiveDay= new Date(current_month_first_day);
            const diffTime = Math.abs(employerJoinDate - currentMonthEffectiveDay);
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)); 
            
            return diffDays;
          
        }else {
          return 0;
        }
    }

function getAmount(id, effectivedate, basic_salary){
    //alert('Komol');
    
    let effectivedate_id = 'effectivedate'+id
    let working_day_id = 'working_days'+id
    let authorized_leave_id = 'authorized_leave'+id
    let unauthorized_leave_id = 'unauthorized_leave'+id
    let weekly_holiday_id = 'weekly_holiday'+id
    let govt_holiday_id = 'govt_holiday'+id
    let eid_duty_id = 'eid_duty'+id
    let month_days_id = 'month_days'+id
    let others_ta_bill_id = 'others_ta_bill'+id
    let meeting_days_id = 'meeting_days'+id
    let basic_salary_id = basic_salary

    //alert(basic_salary_id);
    
    var working_days = parseInt($('#'+working_day_id).val());
    var authorized_leave = parseInt($('#'+authorized_leave_id).val());
    var unauthorized_leave = parseInt($('#'+unauthorized_leave_id).val());
    var weekly_holiday = parseInt($('#'+weekly_holiday_id).val());
    var govt_holiday = parseInt($('#'+govt_holiday_id).val());
    var eid_duty = parseInt($('#'+eid_duty_id).val());
    var month_days = parseInt($('#'+month_days_id).val());
    var meeting_days = parseInt($('#'+meeting_days_id).val());
    var others_ta_bill = parseInt($('#'+others_ta_bill_id).val());
    var basic_salary = parseFloat($('#'+basic_salary_id).val());
    
    let current_month_days = getEffectiveDate(effectivedate);
    //alert(basic_salary);
    let CurPayableDays = parseFloat((month_days - current_month_days)-unauthorized_leave);
    //alert(govt_holiday);
    let d = new Date();
    let specificNumberOfFriday = specificDays('Thursday',MONTHNAMES[d.getMonth()],d.getFullYear(),effectivedate);
    
    if(current_month_days >0){

        let startDate = effectivedate;

        if(current_month_days >13){
            //alert(current_month_days);
            $('#travelling_allowance'+id).val(parseFloat(working_days*200));
            $('#dearness_allowance'+id).val(parseFloat((working_days-meeting_days)*130));
            $('#mobile_bill'+id).val(parseFloat((500/month_days)*CurPayableDays).toFixed(0));
            $('#payable_days'+id).val(parseFloat((month_days - current_month_days)-unauthorized_leave));
            $('#salary'+id).val(parseFloat(((month_days - current_month_days)-unauthorized_leave)*(basic_salary_id/month_days)).toFixed(0));
            $('#weekly_holiday_bill'+id).val(parseFloat((basic_salary_id/30)*weekly_holiday).toFixed(0));
            $('#govt_holiday_bill'+id).val(parseFloat((basic_salary_id/30)*govt_holiday).toFixed(0));
            $('#eid_duty_bill'+id).val(parseFloat(((basic_salary_id/30)*2)*eid_duty).toFixed(0));
            $('#gross_salary'+id).val(parseFloat((((basic_salary_id/month_days)*CurPayableDays) + ((500/month_days)*CurPayableDays) + (200)*working_days + (130)*(working_days-meeting_days) + ((basic_salary_id/30)*(weekly_holiday + govt_holiday)) + others_ta_bill + (((basic_salary_id/30)*2)*eid_duty) )).toFixed(0));
            
        }else{
            //alert(current_month_days);
            $('#travelling_allowance'+id).val(parseFloat(working_days*200));
            $('#dearness_allowance'+id).val(parseFloat((working_days-meeting_days)*130));
            $('#mobile_bill'+id).val(parseFloat((500/month_days)*CurPayableDays).toFixed(0));
            $('#payable_days'+id).val(parseFloat((month_days - current_month_days)-unauthorized_leave));
            $('#salary'+id).val(parseFloat(((month_days - current_month_days)-unauthorized_leave)*(basic_salary_id/month_days)).toFixed(0));
            $('#weekly_holiday_bill'+id).val(parseFloat((basic_salary_id/30)*weekly_holiday).toFixed(0));
            $('#govt_holiday_bill'+id).val(parseFloat((basic_salary_id/30)*govt_holiday).toFixed(0));
            $('#eid_duty_bill'+id).val(parseFloat(((basic_salary_id/30)*2)*eid_duty).toFixed(0));
            $('#gross_salary'+id).val(parseFloat((((basic_salary_id/month_days)*CurPayableDays) + ((500/month_days)*CurPayableDays) + (200)*working_days + (130)*(working_days-meeting_days) + ((basic_salary_id/30)*(weekly_holiday + govt_holiday)) + others_ta_bill + (((basic_salary_id/30)*2)*eid_duty) ) ).toFixed(0));
        }
 
        
    }else{
        //alert('Sarker');
        $('#travelling_allowance'+id).val(parseFloat(working_days*200));
        $('#dearness_allowance'+id).val(parseFloat((working_days-meeting_days)*130));
        $('#mobile_bill'+id).val(parseFloat((500/month_days)*CurPayableDays).toFixed(0));
        $('#payable_days'+id).val(parseFloat((month_days - current_month_days)-unauthorized_leave));
        $('#salary'+id).val(parseFloat(((month_days - current_month_days)-unauthorized_leave)*(basic_salary_id/month_days)).toFixed(0));
        $('#weekly_holiday_bill'+id).val(parseFloat((basic_salary_id/30)*weekly_holiday).toFixed(0));
        $('#govt_holiday_bill'+id).val(parseFloat((basic_salary_id/30)*govt_holiday).toFixed(0));
        $('#eid_duty_bill'+id).val(parseFloat(((basic_salary_id/30)*2)*eid_duty).toFixed(0));
        $('#gross_salary'+id).val(parseFloat((((basic_salary_id/month_days)*CurPayableDays) + ((500/month_days)*CurPayableDays) + (200)*working_days + (130)*(working_days-meeting_days) + ((basic_salary_id/30)*(weekly_holiday + govt_holiday)) + others_ta_bill +(((basic_salary_id/30)*2)*eid_duty) ) ).toFixed(0));
       
    }
}

function specificDays(dayName, monthName, year, effectivedate='') {
  // set names
  let effectivedateCal = new Date(effectivedate); 
  // change string to index of array
  var day = DAYNAMES.indexOf(dayName);
  var month = MONTHNAMES.indexOf(monthName)+1;

  // determine the number of days in month
  var daysinMonth = new Date(year, month, 0).getDate();

  // set counter
  var sumDays=0;

  // iterate over the days and compare to day
  for(var i=1; i<=daysinMonth; i++) {
    let loopDate = new Date(year, month-1, parseInt(i))
    var checkDay = loopDate.getDay();    
    if(effectivedate !=''){
        if(loopDate>effectivedateCal){
            if(day == checkDay) {
              sumDays ++;
            }

        }

    }else{
        if(day == checkDay) {
          sumDays ++;
        }
    }
  }

  // show amount of day names in month
  return sumDays;
}


 function getWorkingDay(id, effectivedate){

    //alert('Komol-T');
    
    let Monthly_Total_Holidays_id = 'Monthly_Total_Holidays'+id
    let month_days_id = 'month_days'+id
    let authorized_leave_id = 'authorized_leave'+id
    let unauthorized_leave_id = 'unauthorized_leave'+id
    let weekly_holiday_id = 'weekly_holiday'+id
    let govt_holiday_id = 'govt_holiday'+id
    let eid_duty_id = 'eid_duty'+id
    let meeting_days_id = 'meeting_days'+id
    let others_ta_bill_id = 'others_ta_bill'+id

    
    var Monthly_Total_Holidays = parseInt($('#'+Monthly_Total_Holidays_id).val());
    var month_days = parseInt($('#'+month_days_id).val());
    var authorized_leave = parseInt($('#'+authorized_leave_id).val());
    var unauthorized_leave = parseInt($('#'+unauthorized_leave_id).val());
    var weekly_holiday = parseInt($('#'+weekly_holiday_id).val());
    var govt_holiday = parseInt($('#'+govt_holiday_id).val());
    var eid_duty = parseInt($('#'+eid_duty_id).val());



    let current_month_days_new = getEffectiveDate(effectivedate);
    //alert(current_month_days_new);
    let CurPayableDays = parseFloat(month_days - current_month_days_new);
    //alert(CurPayableDays);

    let d = new Date();
    let specificNumberOfFriday_new = specificDays('Thursday',MONTHNAMES[d.getMonth()],d.getFullYear(),effectivedate);
    //alert(specificNumberOfFriday_new);

    let CurWorkingDays = parseFloat(  (month_days - ((current_month_days_new + specificNumberOfFriday_new + authorized_leave + unauthorized_leave)  + (weekly_holiday + govt_holiday + eid_duty))));
    //alert(current_month_days_new);

    // Get year, month, and day part from the date
    if(current_month_days_new < 12 ){
        if(CurPayableDays >= CurWorkingDays){
            $('#working_days'+id).val(parseFloat((month_days - (current_month_days_new + specificNumberOfFriday_new)) - (Monthly_Total_Holidays + authorized_leave + unauthorized_leave) + weekly_holiday + govt_holiday + eid_duty));
        }else{
            alert('Working Days shall not be greater than the Payable Days.....11');

        }
        
    }else {
        if(CurPayableDays >= CurWorkingDays){
            $('#working_days'+id).val(parseFloat((month_days - (current_month_days_new + specificNumberOfFriday_new)) - (authorized_leave + unauthorized_leave) + weekly_holiday + govt_holiday + eid_duty));
        }else{
            alert('Working Days shall not be greater than the Payable Days.....2');
            
        }
    }

 }
</script>
    @slot('css')
     <!--Date picker-->
     <link rel="stylesheet" href="{{ asset('vendor/bootstrap_date-picker/css/bootstrap-datepicker3.min.css') }}">
    @endslot
@endcomponent

