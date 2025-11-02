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
    padding:3px 2px;
    font-size:11px;
    line-height:11px;
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

</style>
</head>
<body>
    <table cellpadding="0" cellspacing="0" border="0">
        <thead>
            
            <tr align="center">
                <!--src="{{ asset('images/Polar-Logo- Bangla.jpg') }}"-->
                <td width="100%">
                   <span class="title" style="width: 30%"></span>
                   <span class="data no-border" style="width: 70%"><h1>DHAKA ICE CREAM INDUSTRIES LTD.</h1></span>
               </td>
                
            </tr>
            <tr>
                <td width="100%">
                    <span class="title" style="width: 55%"></span>
                    <span class="data no-border" style="width: 45%"><h2>MDR Salary Sheet</h2></span>
                </td>
                
            </tr>
            <tr>
                <td colspan="2" width="100%">
                    <span class="title" style="width: 33%"></span>
                    <span class="title" style="width: 5%">Month :  </span>
                    <span class="data no-border" style="width: 10%"> {{$Month_Name}} </span>
                    <span class="title" style="width: 5%">Year :  </span>
                    <span class="data no-border" style="width: 10%">2025 </span>

                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">
                    <table cellspacing="0" width="100%" border="1">
                        <thead>
                          <tr bgcolor="1">
                            <th width="2%">Sl</th>
                            <th width="13%" align="left">DB Name</th>
                            <th width="10%" align="left">MDR ID</th>
                            <th width="13%">MDR Name</th>
                            <th width="9%">Depot Name</th>
                            <th align="center" width="8%">Rocket No</th>
                            <th align="center" width="7%">Joining Date</th>
                            <th align="center" width="4%">Payable Days</th>
                            <th align="center" width="4%">Working Days</th>
                            <th align="center" width="4%">Leave</th>
                            <th align="center" width="4%">Absent</th>
                            <th align="center" width="4%">W.Holiday</th>
                            <th align="center" width="4%">G.Holiday</th>
                            <th align="center" width="4%">EID Duty</th>
                            <th align="center" width="9%">Payable Salary</th>
                                                    
                          </tr>
                        </thead>
                        <tbody>
                            <?php $n=1 ?>
                            @foreach ($AttendanceReport as $key=>$data)
                            
                            <tr>
                                <td>{{$key+1}}</td>
                                <td align="left">{{$data->distributors->distributorName or ''}}</td>
                                <td align="left">{{$data->mdrInformations->mdr_idcard or ''}}</td>
                                <td align="left">{{$data->mdrInformations->applicant_name or ''}}</td>
                                <td align="left">{{$data->depots->name or ''}}</td>
                                <td align="left">{{$data->mdrInformations->applicant_mobile or ''}}</td>
                                <td align="center">{{$data->mdrInformations->effectivedate or ''}}</td>
                                <td align="center">{{$data->month_days or ''}}</td>
                                <td align="center">{{$data->working_days or ''}}</td>
                                <td align="center">{{$data->authorized_leave or ''}}</td>
                                <td align="center">{{$data->unauthorized_leave or ''}}</td>
                                <td align="center">{{$data->weekly_holiday or ''}}</td>
                                <td align="center">{{$data->govt_holiday or ''}}</td>
                                <td align="center">{{$data->eid_duty or ''}}</td>
                                <td align="right">{{number_format($data['salary'],2)}}</td> 
                            </tr>
                                
                                
                            <?php $n++ ?>
                            @endforeach
                        </tbody>
                        <tfoot>
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
                                <td>Total </td>
                                <td> : </td>
                                <td align="right"></td>
                                <td align="right">{{number_format($AttendanceReport->sum('salary'),2)}}</td>
                            </tr>
                        </tfoot>
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
        </tbody>
    </table>
</body>    
</html>