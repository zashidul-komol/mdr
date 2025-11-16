<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="{{ public_path().'/css/pdf.min.css' }}">

<style>
body{
    margin:0;
}
td{
    padding:2px 2px;
    font-size:11px;
    line-height:10px;
    font-family:Arial, Helvetica, sans-serif;
    border:0px solid #000;
}

h1{
    padding:0;
    margin:0;
    font-size:26px;
    line-height:15px;
}
h2{
    padding:0;
    margin:0;
    font-size:18px;
    line-height:15px;
}
h2 span{
    padding-left:10px;
    margin:0;
    font-size:18px;
    line-height:15px;
    font-weight:normal;
}
.page-break {
    page-break-inside:avoid;  page-break-after:always;
}
table {
  page-break-inside: avoid !important;
}
thead { display: table-header-group }
tfoot { display: table-row-group }
tr { page-break-inside: avoid }

</style>
</head>
<body>
    <table id="main-table" cellpadding="0" cellspacing="0" border="0">
        <thead>
            
            <tr>
                <!--src="{{ asset('images/Polar-Logo- Bangla.jpg') }}"-->
                <td width="100%">
                    <span class="title" style="width: 30%"><img src="{{public_path('images/PolarLogoBangla.png')}}" alt="Girl in a jacket" style="width:170px;height:70px;"></span>
                    <span class="data no-border" style="width: 70%"><h1>DHAKA ICE CREAM INDUSTRIES LTD.</h1></span>
                
            </tr>
            <tr>
                <td width="100%">
                    <span class="title" style="width: 50%"></span>
                    <span class="data no-border" style="width: 50%"><h2>MDR TA/DA Bill</h2></span>
                
            </tr>
            <tr>
                <td width="100%">
                    <span class="title" style="width: 40%"></span>
                    <span class="title" style="width: 10%">Month :  </span>
                    <span class="data no-border" style="width: 15%"> {{$Month_Name}}</span>
                    <span class="title" style="width: 10%">Year :  </span>
                    <span class="data no-border" style="width: 15%">2025</span>

                </td>
                        
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">
                    <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="96%" border="1" >
                        <thead>
                          <tr bgcolor="1">
                            <th width="1%" align="left">SL No.</th>
                            <th width="10%" align="left">DB Name</th>
                            <th width="7%" align="left">MDR ID</th>
                            <th width="10%">MDR Name</th>
                            <th width="7%">Depot</th>
                            <th align="center" width="7%">Rocket No</th>
                            <th align="center" width="6%">Joining Date</th>
                            <th align="center" width="3%">Work Days</th>
                            <th align="center" width="3%">Meet Days</th>
                            <th align="center" width="3%">Lea  ve</th>
                            <th align="center" width="3%">Abs ent</th>
                            <th align="center" width="4%">W.H. day</th>
                            <th align="center" width="4%">G.H. day</th>
                            <th align="center" width="3%">EID Duty</th>
                            <th align="center" width="3%">TA</th>
                            <th align="center" width="3%">DA</th>
                            <th align="center" width="3%">W.H.day Bill</th>
                            <th align="center" width="3%">G.H.day Bill</th>
                            <th align="center" width="3%">EID Duty Bill</th>
                            <th align="center" width="3%">Mob</th>
                            <th align="center" width="10%">Total</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                            <?php $n=1 ?>
                            @foreach ($AttendanceReport as $key=>$data)
                          <tr style="page-break-inside: avoid;">
                            <td width="1%">{{$key+1}}</td>
                            <td align="left">{{$data->distributors->distributorName  ??  ''}}</td>
                            <td align="left">{{$data->mdrInformations->mdr_idcard  ??  ''}}</td>
                            <td align="left">{{$data->mdrInformations->applicant_name  ??  ''}}</td>
                            <td align="left">{{$data->depots->name  ??  ''}}</td>
                            <td align="left">{{$data->mdrInformations->applicant_mobile  ??  ''}}</td>
                            <td align="center">{{$data->mdrInformations->effectivedate  ??  ''}}</td>
                            <td align="center">{{$data->working_days  ??  ''}}</td>
                            <td align="center">{{$data->meeting_days  ??  ''}}</td>
                            <td align="center">{{$data->authorized_leave  ??  ''}}</td>
                            <td align="center">{{$data->unauthorized_leave  ??  ''}}</td>
                            <td align="center">{{$data->weekly_holiday  ??  ''}}</td>
                            <td align="center">{{$data->govt_holiday  ??  ''}}</td>
                            <td align="center">{{$data->eid_duty  ??  ''}}</td>
                            <td align="center">{{$data->travelling_allowance  ??  ''}}</td>
                            <td align="center">{{$data->dearness_allowance  ??  ''}}</td>
                            <td align="center">{{$data->weekly_holiday_bill  ??  ''}}</td>
                            <td align="center">{{$data->govt_holiday_bill  ??  ''}}</td>
                            <td align="center">{{$data->eid_duty_bill  ??  ''}}</td>
                            <td align="center">{{$data->mobile_bill  ??  ''}}</td>
                            @php
                                $Total_Salary = $data->travelling_allowance + $data->dearness_allowance + $data->weekly_holiday_bill + $data->govt_holiday_bill + $data->eid_duty_bill + $data->mobile_bill;
                            @endphp
                            <td align="right">{{number_format($Total_Salary,2)}}</td>
                            
                          </tr>
                            <?php $n++ ?>
                            @endforeach

                        </tbody>
                        <tbody>
                            
                          <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total </td>
                            <td> : </td>

                            <td align="center">{{number_format($AttendanceReport->sum('travelling_allowance'),0)}}</td>
                            <td align="center">{{number_format($AttendanceReport->sum('dearness_allowance'),0)}}</td>
                            <td align="center">{{number_format($AttendanceReport->sum('weekly_holiday_bill'),2)}}</td>
                            <td align="center">{{number_format($AttendanceReport->sum('govt_holiday_bill'),2)}}</td>
                            <td align="center">{{number_format($AttendanceReport->sum('eid_duty_bill'),2)}}</td>
                            <td align="center">{{number_format($AttendanceReport->sum('mobile_bill'),0)}}</td>
                            <td align="center">{{number_format($AttendanceReport->sum('travelling_allowance') + $AttendanceReport->sum('dearness_allowance') + $AttendanceReport->sum('weekly_holiday_bill') + $AttendanceReport->sum('govt_holiday_bill') + $AttendanceReport->sum('eid_duty_bill') + $AttendanceReport->sum('mobile_bill'),2)}}</td>
                          </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            
        </tbody>
        
    </table>
    <br/>
</body>
</html>