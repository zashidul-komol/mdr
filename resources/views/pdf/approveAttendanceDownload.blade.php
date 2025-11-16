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
    line-height:30px;
}
h2{
    padding:0;
    margin:0;
    font-size:18px;
    line-height:24px;
}
h2 span{
    padding-left:10px;
    margin:0;
    font-size:18px;
    line-height:24px;
    font-weight:normal;
}

</style>
</head>
<body>
    <table id="main-table" cellpadding="0" cellspacing="0" border="1">
        <thead>
            <tr>
                
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">
                    <table class="subtable">
                        <tr>
                            <td width="50%"><span class="title" style="width: 20%"></span><span class="data no-border" style="width: 30%"></span></td>
                            <td width="50%"><span class="title" style="width: 20%"></span><span class="data no-border" style="width: 30%"></span></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <!--src="{{ asset('images/Polar-Logo- Bangla.jpg') }}"-->
                <td width="100%">
                    <span class="title" style="width: 30%"><img src="{{public_path('images/PolarLogoBangla.png')}}" alt="Girl in a jacket" style="width:150px;height:60px;"></span>
                    <span class="data no-border" style="width: 70%"><h1>DHAKA ICE CREAM INDUSTRIES LTD.</h1></span>
                
            </tr>
            <tr>
                <td width="100%">
                    <span class="title" style="width: 36%"></span>
                    <span class="data no-border" style="width: 64%"><h4>80, Shaheed Tajuddin Ahmed Sarani,  Tejgaon Industrial Area, Dhaka-1208</h4></span>
                
            </tr>
            <tr>
                <td width="100%">
                    <span class="title" style="width: 15%">Requisition  #  </span>
                    <span class="data no-border" style="width: 8%">{{$id}}</span>
                    <span class="title" style="width: 12%">Department :  </span>
                    <span class="data no-border" style="width: 25%">{{$RequisitionDetails[0]->department->name  ??  ''}}</span>
                    <span class="title" style="width: 10%">Section :  </span>
                    <span class="data no-border" style="width: 15%">{{$RequisitionDetails[0]->section->name  ??  ''}}</span>
                    <span class="title" style="width: 5%">Date :  </span>
                    <span class="data no-border" style="width: 10%">{{\Carbon\Carbon::parse($requisitionDate[0]->date)->format('d-m-Y H:i:s') }}</span>

                </td>
                        
            </tr>
            <tr>
                <td width="100%">
                    <span class="title" style="width: 20%">Requisition Details :  </span>
                    <span class="data no-border" style="width: 80%"></span>
                    
                </td>
                        
            </tr>
            <tr>
                <td colspan="2">
                    <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%" border="1">
                    <thead>
                      <tr bgcolor="1">
                        <th width="2%">Sl</th>
                        <th width="8%" align="left">Prod Type</th>
                        <th width="18%">Product Name</th>
                        <th width="14%">Required For</th>
                        <th width="7%">Qnty</th>
                        <th width="5%">Consumption</th>
                        <th align="center" width="10%">Unit Price</th>
                        <th align="center" width="10%">Total Price</th>
                        <th width="8%" align="center">Pr. Stock</th>
                        <th width="18%">Remarks</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($RequisitionDetails as $key=>$data)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$subcategories[$key][0]}}</td>
                        <td>{{ $data->product->name  ??  '' }}</td>
                        <td>

                          @if(isset($particulars[$key]) && !empty($particulars[$key][0]))
                          
                          {{$particulars[$key][0]}}
                        @else
                          NA
                        @endif
                        </td>
                        <td align="left">{{$reqQuantities[$key]}}</td>
                        <td align="center">{{$consumptions[$key]}}</td>
                        <td align="right">{{ number_format($data['unitprice'],0) }}</td>
                        <td align="right">{{ number_format($data['totalprice'],0) }}</td>
                        <td align="center">{{ $data->present_stock  ??  '' }}</td>
                        <td>{{ $data->remarks  ??  '' }}</td>
                        
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
                        <th align="right">Total Price : </th>
                        <th align="right">{{number_format($RequisitionDetails->sum('totalprice'),0)}}</th>
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
                      </tr>
                      <tr>
                        <td colspan="10">Consumption column shows last 6 months approved products consumption.</td>
                        
                      </tr>
                                              
                    </tbody>
                    
                  </table>
                  
                      
                    
                </td>
            </tr>
            <tr>
                <td>Comments : </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table id="datatable" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%" border="1">
                    <tbody>
                        @foreach ($RequisitionLogs as $key=>$data)
                      <tr>
                        <td width="5%">{{$key+1}}</td>
                        <td width="15%">{{ $data->action_name  ??  '' }}</td>
                        <td width="2%">:</td>
                        <td width="20%%">{{ $data->user->name  ??  '' }}</td>
                        <td width="58%">{{$data->comments  ??  ''}}</td>
                      </tr>
                        @endforeach
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
                <td colspan="2">Note : This is a computer-generated requisition. No signature is required.</td>
            </tr>
        </tbody>
    </table>
    <br/>
</body>
</html>