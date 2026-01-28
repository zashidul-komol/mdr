@extends('layouts.admin')

@section('content')

<div class="content-header text-center">
    <ul class="breadcrumbs">
        <li>
            <i class="fa fa-home"></i>
            <h4 class="section-subtitle"><b>MDR Monthly Attendance View</b></h4>
        </li>
    </ul>
</div>

<div class="panel">
<div class="panel-content">

{{ Form::open([
    'route' => ['mdrattendances.update', $MdrInformations[0]->attendance_id],
    'method' => 'PUT',
    'class' => 'form-horizontal'
]) }}

@csrf

<div class="form-group row">

<div class="col-sm-2">
<label>Region</label>
{{ Form::text('region_name',$RegionName,['class'=>'form-control','readonly']) }}
</div>

<div class="col-sm-2">
<label>Date</label>
{{ Form::text('salary_date',$TodayDate,['class'=>'form-control','readonly']) }}
</div>

<div class="col-sm-2">
<label>Month</label>
{{ Form::text('month_name',$MonthName,['class'=>'form-control','readonly']) }}
</div>

<div class="col-sm-2">
<label>Year</label>
{{ Form::text('year',$year,['class'=>'form-control','readonly']) }}
</div>

</div>

<div class="table-responsive">

<table class="table table-striped table-hover">

<thead>
<tr>
<th>SL</th>
<th>DB Name</th>
<th>MDR Name</th>
<th>Month Days</th>
<th>Leave</th>
<th>Unauthorize</th>
<th>Weekly</th>
<th>Govt</th>
<th>Meeting</th>
<th>Others TA</th>
<th>EID</th>
<th>Working</th>
<th>Payable</th>
<th>Salary</th>
<th>WH Bill</th>
<th>GH Bill</th>
<th>EID Bill</th>
<th>TA</th>
<th>DA</th>
<th>Mobile</th>
<th>Gross</th>
</tr>
</thead>

<tbody>

@foreach($MdrInformations as $i=>$data)

<input type="hidden" name="id[]" value="{{ $data->id }}">

<tr>
<td>{{ $i+1 }}</td>
<td>{{ $data->distributors->distributorName ?? '' }}</td>
<td>{{ $data->mdrInformations->applicant_name ?? '' }}</td>

<td><input readonly name="month_days[]" class="form-control" value="{{ $data->month_days }}"></td>
<td><input readonly name="authorized_leave[]" class="form-control" value="{{ $data->authorized_leave }}"></td>
<td><input readonly name="unauthorized_leave[]" class="form-control" value="{{ $data->unauthorized_leave }}"></td>
<td><input readonly name="weekly_holiday[]" class="form-control" value="{{ $data->weekly_holiday }}"></td>
<td><input readonly name="govt_holiday[]" class="form-control" value="{{ $data->govt_holiday }}"></td>
<td><input readonly name="meeting_days[]" class="form-control" value="{{ $data->meeting_days }}"></td>
<td><input readonly name="others_ta_bill[]" class="form-control" value="{{ $data->others_ta_bill }}"></td>
<td><input readonly name="eid_duty[]" class="form-control" value="{{ $data->eid_duty }}"></td>
<td><input readonly name="working_days[]" class="form-control" value="{{ $data->working_days }}"></td>
<td><input readonly name="payable_days[]" class="form-control" value="{{ $data->payable_days }}"></td>
<td><input readonly name="salary[]" class="form-control" value="{{ $data->salary }}"></td>
<td><input readonly name="weekly_holiday_bill[]" class="form-control" value="{{ $data->weekly_holiday_bill }}"></td>
<td><input readonly name="govt_holiday_bill[]" class="form-control" value="{{ $data->govt_holiday_bill }}"></td>
<td><input readonly name="eid_duty_bill[]" class="for
