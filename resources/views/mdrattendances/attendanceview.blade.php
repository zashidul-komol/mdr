@extends('layouts.admin')

@section('content')

<form id="updateReqEntry"
      action="{{ route('mdrattendances.updateAttendance') }}"
      method="POST"
      style="position:relative;overflow:hidden;">

@csrf

<input type="hidden" name="attendance_id" value="{{ $MdrInformations[0]->attendance_id }}">
<input type="hidden" name="attendance_status" id="attendance_status">

<div class="content-header">
    <div class="text-center">
        <ul class="breadcrumbs">
            <li>
                <i class="fa fa-home"></i>
                <h4 class="section-subtitle"><b>MDR Monthly Attendance View</b></h4>
            </li>
        </ul>
    </div>
</div>

<div class="panel">
<div class="panel-content">

<div class="form-group row">

<div class="col-sm-2">
<label>Depot</label>
<input class="form-control" value="{{ $DepotName }}" readonly>
</div>

<div class="col-sm-2">
<label>Date</label>
<input class="form-control" value="{{ $TodayDate }}" readonly>
</div>

<div class="col-sm-2">
<label>Month</label>
<input class="form-control" value="{{ $MonthName }}" readonly>
</div>

<div class="col-sm-2">
<label>Year</label>
<input class="form-control" value="{{ $year }}" readonly>
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
<th>Absent</th>
<th>Weekly Holiday</th>
<th>Govt Holiday</th>
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

@foreach($MdrInformations as $i => $data)

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
<td><input readonly name="eid_duty_bill[]" class="form-control" value="{{ $data->eid_duty_bill }}"></td>
<td><input readonly name="travelling_allowance[]" class="form-control" value="{{ $data->travelling_allowance }}"></td>
<td><input readonly name="dearness_allowance[]" class="form-control" value="{{ $data->dearness_allowance }}"></td>
<td><input readonly name="mobile_bill[]" class="form-control" value="{{ $data->mobile_bill }}"></td>
<td><input readonly name="gross_salary[]" class="form-control" value="{{ $data->gross_salary }}"></td>

</tr>

@endforeach

</tbody>
</table>

</div>

<div class="form-group">
<label>Comments</label>
<textarea name="comments" class="form-control" rows="2" maxlength="250"></textarea>
</div>

<div class="text-center">

<button type="submit"
        class="btn btn-primary"
        name="action"
        value="return"
        onclick="$('#attendance_status').val('return')">
RETURN
</button>

<button type="submit"
        class="btn btn-success"
        name="action"
        value="verify"
        onclick="$('#attendance_status').val('verify')">
FORWARD / VERIFY
</button>

</div>

</div>
</div>

</form>

@endsection
