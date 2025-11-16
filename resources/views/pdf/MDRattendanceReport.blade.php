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

</style>
</head>
<body>
    <table id="main-table" cellpadding="0" cellspacing="0" border="0">
        <thead>
            
            <tr align="center">
                <!--src="{{ asset('images/Polar-Logo- Bangla.jpg') }}"-->
                <td width="100%">
                   <span class="title" style="width: 30%"></span>
                   <span class="data no-border" style="width: 70%"><h1>DHAKA ICE CREAM INDUSTRIES LTD.</h1></span>
                
            </tr>
            <tr>
                <td width="100%">
                    <span class="title" style="width: 50%"></span>
                    <span class="data no-border" style="width: 50%"><h2>MDR Summary Salary Sheet</h2></span>
                
            </tr>
        </thead>
        <tbody>
            
            <tr>
                <td width="100%">
                    <span class="title" style="width: 15%">Depot :  </span>
                    <span class="data no-border" style="width: 8%">{{$Depot_Name}}</span>
                    <span class="title" style="width: 12%"></span>
                    <span class="data no-border" style="width: 25%"></span>
                    <span class="title" style="width: 10%">Month :  </span>
                    <span class="data no-border" style="width: 15%"> {{$Month_Name}}</span>
                    <span class="title" style="width: 5%">Year :  </span>
                    <span class="data no-border" style="width: 10%">2025</span>

                </td>
                        
            </tr>
            <tr>
                <td colspan="2">
                    <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%" border="1">
                    <thead>
                      <tr bgcolor="1">
                        <th width="2%">Sl</th>
                        <th width="15%" align="left">DB Name</th>
                        <th width="10%" align="left">MDR ID</th>
                        <th width="15%">MDR Name</th>
                        <th align="center" width="6%">Rocket No</th>
                        <th align="center" width="6%">Joining Date</th>
                        <th align="center" width="5%">Working Days</th>
                        <th align="center" width="4%">Leave</th>
                        <th align="center" width="4%">Absent</th>
                        <th align="center" width="4%">Off Day</th>
                        <th align="center" width="6%">Salary</th>
                        <th align="center" width="6%">Off Day Bill</th>
                        <th align="center" width="5%">TA</th>
                        <th align="center" width="5%">DA</th>
                        <th align="center" width="4%">Mobile Bill</th>
                        <th align="center" width="7%">Gross Salary</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($AttendanceReport as $key=>$data)
                      <tr>
                        <td align="left">{{$key+1}}</td>
                        <td align="left">{{$data->distributors->distributorName  ??  ''}}</td>
                        <td align="left">{{$data->mdrInformations->mdr_idcard  ??  ''}}</td>
                        <td align="left">{{$data->mdrInformations->applicant_name  ??  ''}}</td>
                        <td align="left">{{$data->mdrInformations->applicant_mobile  ??  ''}}</td>
                        <td align="center">{{$data->mdrInformations->effectivedate  ??  ''}}</td>
                        <td align="center">{{$data->working_days  ??  ''}}</td>
                        <td align="center">{{$data->authorized_leave  ??  ''}}</td>
                        <td align="center">{{$data->unauthorized_leave  ??  ''}}</td>
                        <td align="center">{{$data->extra_duty  ??  ''}}</td>
                        <td align="right">{{number_format($data['salary'],2)}}</td>
                        <td align="right">{{$data->off_day_bill  ??  ''}}</td>
                        <td align="center">{{$data->travelling_allowance  ??  ''}}</td>
                        <td align="center">{{$data->dearness_allowance  ??  ''}}</td>
                        <td align="right">{{$data->mobile_bill  ??  ''}}</td>
                        <td align="right">{{number_format($data['gross_salary'],2)}}</td>
                        
                        
                      </tr>
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
                      </tr>
                      <tr style="font-style: revert;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total </td>
                        <td> : </td>
                        <td align="right">{{number_format($AttendanceReport->sum('salary'),2)}}</td>
                        <td align="right">{{number_format($AttendanceReport->sum('off_day_bill'),2)}}</td>
                        <td align="center">{{number_format($AttendanceReport->sum('travelling_allowance'),0)}}</td>
                        <td align="center">{{number_format($AttendanceReport->sum('dearness_allowance'),0)}}</td>
                        <td align="right">{{number_format($AttendanceReport->sum('mobile_bill'),0)}}</td>
                        <td align="right">{{number_format($AttendanceReport->sum('gross_salary'),2)}}</td>
                      </tr>
                                                                    
                    </tbody>
                    
                  </table>
                  
                      
                    
                </td>
            </tr>
            <tr>
                <td></td>
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