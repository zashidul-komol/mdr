@extends('layouts.admin')

@section('content')

<form id="updateReqEntry"
      action="{{ route('merchandiserattendances.updateAttendance') }}"
      method="POST"
      style="position: relative; overflow: hidden;">

    @csrf

    <input type="hidden" name="attendance_id"
       value="{{ $MdrInformations->first()->attendance_id }}">

    <input type="hidden" name="attendance_status" id="attendance_status">

    <div class="content-header">
        <div class="text-center">
            <ul class="breadcrumbs">
                <li>
                    <i class="fa fa-home"></i>
                    <h4 class="section-subtitle">
                        <b>Merchandiser Monthly Attendance View</b>
                    </h4>
                </li>
            </ul>
        </div>
    </div>

    <div class="panel">
            <div class="panel-content">
                       
                <div class="table-responsive">
                        <div class="form-group">

                          <label for="inputName" class="col-sm-1 ">Depot</label>

                          <div class="col-sm-2">
                            {{Form::text('depot_name',$DepotName,array('class' => 'form-control', 'readonly' => 'true'))}}
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
                        <th width="7%" align="left">Merchandiser Name</th>
                        <th hidden="true">ID</th>
                        <th width="5%" align="left">Month Days</th>
                        <th width="3%" align="left">Leave</th>
                        <th width="3%" align="left">Absent</th>
                        <th width="3%" align="left">Weekly Holiday</th>
                        <th width="3%" align="left">Govt Holiday</th>
                        <th width="4%" align="left">Meeting Days</th>
                        <th width="4%" align="left">Others TA</th>
                        <th width="4%" align="left">EID Duty</th>
                        <th width="5%" align="left">Working Days</th>
                        <th width="5%" align="left">Payable Days</th>
                        <th width="7%" align="left">Salary</th>
                        <th width="6%" align="left">W.Holiday Bill</th>
                        <th width="6%" align="left">G.Holiday Bill</th>
                        <th width="6%" align="left">EID Duty Bill</th>
                        <th width="5%" align="left">TA</th>
                        <th width="5%" align="letf">DA</th>
                        <th width="5%" align="left">Mobile</th>
                        <th width="10%" align="right">G.Salary</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($MdrInformations as $data)
                        <tr>
                            <td>{{ $i++ }}</td>

                            <td>{{ $data->merchandiser_informations->applicant_name ?? '' }}</td>

                            <td hidden>
                                {{ Form::text('id[]', $data->id, ['class'=>'form-control','readonly']) }}
                            </td>

                            <td>{{ Form::text('month_days[]', $data->month_days, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('authorized_leave[]', $data->authorized_leave, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('unauthorized_leave[]', $data->unauthorized_leave, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('weekly_holiday[]', $data->weekly_holiday, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('govt_holiday[]', $data->govt_holiday, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('meeting_days[]', $data->meeting_days, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('others_ta_bill[]', $data->others_ta_bill, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('eid_duty[]', $data->eid_duty, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('working_days[]', $data->working_days, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('payable_days[]', $data->payable_days, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('salary[]', $data->salary, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('weekly_holiday_bill[]', $data->weekly_holiday_bill, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('govt_holiday_bill[]', $data->govt_holiday_bill, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('eid_duty_bill[]', $data->eid_duty_bill, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('travelling_allowance[]', $data->travelling_allowance, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('dearness_allowance[]', $data->dearness_allowance, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('mobile_bill[]', $data->mobile_bill, ['class'=>'form-control','readonly']) }}</td>
                            <td>{{ Form::text('gross_salary[]', $data->gross_salary, ['class'=>'form-control','readonly']) }}</td>
                        </tr>
                        @endforeach
                        </tbody>

                  </table>
                </div>
                <div align="left" class="table-responsive">
                  {{Form::label('  &nbsp; &nbsp; Comments',null,array('class' => 'control-label'))}}
                  {{Form::textarea('comments',null,array('class' => 'form-control max-length','rows'=>2,'maxlength'=>'250'))}}
                </div>
        
                <button type="submit" class="btn btn-primary"
                        onclick="$('#attendance_status').val('return')">
                    RETURN
                </button>

                <button type="submit" class="btn btn-success"
                        onclick="$('#attendance_status').val('verify')">
                    FORWARD / VERIFY
                </button>

            
            </div>
            
        
            
    </div>

</form>


@component('common_pages.selectize')
@include('common_pages.max_length')
<script src="{{ asset('vendor/bootstrap_date-picker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">

        $('.datepicker').datepicker({ format: "yyyy-mm-dd",todayHighlight: true,autoclose:true});

        //get shops  ??  distributor
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

 
    @slot('css')
     <!--Date picker-->
     <link rel="stylesheet" href="{{ asset('vendor/bootstrap_date-picker/css/bootstrap-datepicker3.min.css') }}">
    @endslot
@endcomponent

@endsection

