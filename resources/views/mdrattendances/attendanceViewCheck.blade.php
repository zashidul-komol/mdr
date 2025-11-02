@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="text-center">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="#"><h4 class="section-subtitle"><b>MDR Monthly Attendance View</b></h4></a></li>
        </ul>
    </div>
</div>
    <div class="panel">
            <div class="panel-content">
                       
                {{ Form::model($MdrInformations,array('route' => array('mdrattendances.update',$MdrInformations[0]->attendance_id),'method' => 'PUT','enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}

                
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
                        <th width="7%" align="left">DB Name</th>
                        <th width="7%" align="left">MDR Name</th>
                        <th hidden="true">ID</th>
                        <th width="5%" align="left">Month Days</th>
                        <th width="3%" align="left">Leave</th>
                        <th width="3%" align="left">Unauthorize Leave</th>
                        <th width="3%" align="left">Weekly Holiday</th>
                        <th width="3%" align="left">Govt Holiday</th>
                        <th width="4%" align="left">Meeting Days</th>
                        <th width="4%" align="left">Others TA</th>
                        <th width="4%" align="left">EID Duty</th>
                        <th width="4%" align="left">Working Days</th>
                        <th width="4%" align="left">Payable Days</th>
                        <th width="7%" align="left">Salary</th>
                        <th width="6%" align="left">W.Holiday Bill</th>
                        <th width="6%" align="left">G.Holiday Bill</th>
                        <th width="7%" align="left">EID Duty Bill</th>
                        <th width="5%" align="left">TA</th>
                        <th width="5%" align="letf">DA</th>
                        <th width="5%" align="left">Mobile</th>
                        <th width="10%" align="right">G.Salary</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php ($i=1)
                        @foreach ($MdrInformations as $data)
                      <tr>
                        <td>{{$i}}</td>
                        <td>{{$data->distributors->distributorName or ''}}</td>
                        <td>{{$data->mdrInformations->applicant_name or ''}}</td>
                        <td hidden="true">{{Form::text('id[]',$data->id,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('month_days[]',$data->month_days,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('authorized_leave[]',$data->authorized_leave,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('unauthorized_leave[]',$data->unauthorized_leave,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('weekly_holiday[]',$data->weekly_holiday,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('govt_holiday[]',$data->govt_holiday,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('meeting_days[]',$data->meeting_days,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('others_ta_bill[]',$data->others_ta_bill,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('eid_duty[]',$data->eid_duty,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('working_days[]',$data->working_days,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('payable_days[]',$data->payable_days,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('salary[]',$data->salary,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('weekly_holiday_bill[]',$data->weekly_holiday_bill,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('govt_holiday_bill[]',$data->govt_holiday_bill,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('eid_duty_bill[]',$data->eid_duty_bill,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('travelling_allowance[]',$data->travelling_allowance,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('dearness_allowance[]',$data->dearness_allowance,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('mobile_bill[]',$data->mobile_bill,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                        <td>{{Form::text('gross_salary[]',$data->gross_salary,array('class' => 'form-control', 'readonly' => 'true'))}}</td>
                      </tr>
                        @php ($i=$i+1)
                        @endforeach
                    </tbody>
                  </table>
                  <table id="datatable" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                    <tbody>
                        @foreach ($AttendanceLogs as $key=>$data)
                      <tr>
                        <td width="2%">{{$key+1}}</td>
                        <td width="12%">{{ $data->action_name or '' }}</td>
                        <td width="1%">:</td>
                        <td width="15%%">{{ $data->user->name or '' }}</td>
                        <td width="15%%">{{ $data->created_at or '' }}</td>
                        <td width="55%">{{$data->comments or ''}}</td>
                      </tr>
                        @endforeach
                    </tbody>
                  </table>
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
    $('#travelling_allowance'+id).val(parseFloat(working_days*75).toFixed(2));
    $('#dearness_allowance'+id).val(parseFloat(working_days*75).toFixed(2));
    $('#mobile_bill'+id).val(parseFloat(working_days*(500/26)).toFixed(2));
    $('#salary'+id).val(parseFloat(working_days*(10000/26)).toFixed(2));
    $('#gross_salary'+id).val(parseFloat((((10000/26)+(500/26)+75+75))*working_days).toFixed(2));
 
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

