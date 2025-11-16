<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="{{ public_path().'/css/deedpaper.css' }}">

<style type="text/css">
	body {
		font-size:14px;
		line-height:22px;
		font-family:'bangla-bold', sans-serif;
	}
	.cTr{
		width: 100%;
	}
	.fl{
		float: left;
	}
	.bbt{
		font-size: 12px;
		font-family:english;
		border-bottom:2px dotted #000;
	}
	.bb{ border-bottom:1px dotted #000;}

</style>

</head>
<body>
	<table id="main-table" cellpadding="0" cellspacing="0" border="0">
        <tbody>
            <tr>
                <!--src="{{ asset('images/Polar-Logo- Bangla.jpg') }}"-->
                <td width="100%">
                    <span class="title" style="width: 30%"><img src="{{public_path('images/PolarLogoBangla.png')}}" alt="Girl in a jacket" style="width:150px;height:100px;"></span>
                </td>    
            </tr>
       </tbody>
   </table>
	<div style="height:00px">&nbsp;</div>
    <h1 class="text-center mainTitle"><span>Appointment Letter</span></h1>
    <div class="cTr pb-sm">
    	<div class="fl" style="width:75%;">Ref. DIIL/HR/{{$AppointmentLetter[0]->distributor->dbcode  ??  'Null'}}/{{$AppointmentLetter[0]->updated_at->format('Y')}}/{{$id}} </div> 
	    <div class="fl" style="width:25%;">Date: {{$AppointmentLetter[0]->updated_at->format('d-M-Y')}} </div>   		
	</div>
	</div>
	<div style="height:50px"> </div>
	<div style="height:20px">Mr. {{$AppointmentLetter[0]->application_details[0]->applicant_name}}</div>
	<div style="height:20px">S/O : {{$AppointmentLetter[0]->application_details[0]->applicant_fathers_name}}</div>
	<div style="height:70px">Address: {{$AppointmentLetter[0]->application_details[0]->applicant_address}}</div>
	<div style="height:50px"><b>Sub : </b>Appointment as Apprentice.</div>
	<div style="height:40px">Dear Mr.  {{$AppointmentLetter[0]->application_details[0]->applicant_name}}</div>
    <div style="height:40px"><b>Apprenticeship </b> </div>
    <div style="height:100px">We are pleased to inform that you have been assigned as an Apprentice at Sales & Distribution dept. of Dhaka Ice Cream Industries Limited with effect from <b>{{$AppointmentLetter[0]->application_details[0]->effectivedate}}.</b> The assignment is for MDR, initially for 6 months, may be renewed up to 24 months gradually, for which you will receive an allowance of TK.11000/= per month. TA/DA@150/- per day will be applicable as per attendance. You will report to ASE/SO as applicable.   </div>
    <div style="height:50px">We expect your sincere efforts for successful completion of the assignment.  </div>
    <div style="height:30px">Thanking You,  </div>
    <div style="height:30px">Yours faithfully,  </div>
    <div style="height:40px">For <b>Dhaka Ice Cream Industries Limited</b></div>
    <img class="profile-user-img img-responsive img-circle" src="{{ public_path('storage/signature/Sign-HOHR.jpg') }}" />
    <div style="height:20px">Kazi Saiful Islam</div>
    <div style="height:40px">Head of HR,Admin&IT</div>
    <div style="height:40px">CC: Finance & Accounts Department</div>
</body>
</html>
