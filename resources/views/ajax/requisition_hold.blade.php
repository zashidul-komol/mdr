<form id="updateReqEntry" style="position: relative;overflow: hidden;" 
    action="{{ route('requisitions.updateRequisition') }}" method="post">
    <input type="hidden" name="requisition_id" value="{{$requisition_id}}">
    <input type="hidden" name="requisition_status" id="requisition_status" value="">
  {{ csrf_field() }} 
    <div class="row">
        <div class="panel">
            <div class="panel-content">
                <div class="table-responsive">
                    <table id="datatable" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                        <thead>
                      <tr>
                        <th width="2%">Sl</th>
                        <th width="7%" align="left">Prod Type</th>
                        <th width="17%">Product Name</th>
                        <th width="13%">Required For</th>
                        <th width="6%">Qnty</th>
                        <th width="5%">Consumption</th>
                        <th align="center" width="9%">Unit Price</th>
                        <th align="center" width="9%">Total Price</th>
                        <th width="8%" align="center">Pr. Stock</th>
                        <th width="8%" align="center">Req. Date</th>
                        <th width="16%">Remarks</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($RequisitionDetails as $key=>$data)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$subcategories[$key][0]}}</td>
                        <td>{{ $data->product->name or '' }}</td>
                        <td>

                          @if(isset($particulars[$key]) && !empty($particulars[$key][0]))
                          
                          {{$particulars[$key][0]}}
                        @else
                          NA
                        @endif
                        </td>
                        <td align="left">{{$reqQuantities[$key]}}</td>
                        <td align="center">{{$consumptions[$key]}}</td>
                        <td align="right">{{ number_format($data['unitprice'],2) }}</td>
                        <td align="right">{{ number_format($data['totalprice'],2) }}</td>
                        <td align="center">{{ $data->present_stock or '' }}</td>
                        <td align="center">{{ $data->requiredDate or '' }}</td>
                        <td>{{ $data->remarks or '' }}</td>
                        
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
                        <th align="right">Total Price : </th>
                        <th align="right">{{number_format($RequisitionDetails->sum('totalprice'),0)}}</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <th width="2%">Comments</thth>
                        <th width="10%"></th>
                        <th width="17%"></th>
                        <th width="17%"></th>
                        <th width="5%" align="right"></th>
                        <th width="9%" align="right"></th>
                        <th width="10%" align="right"></th>
                        <th width="10%"></th>
                        <th width="10%"></th>
                        <th width="5%"></th>
                        <th width="5%"></th>
                      </tr>
                        
                    </tbody>
                    
                  </table>
                  <table id="datatable" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                    <tbody>
                        @foreach ($RequisitionLogs as $key=>$data)
                      <tr>
                        <td width="5%">{{$key+1}}</td>
                        <td width="10%">{{ $data->action_name or '' }}</td>
                        <td width="1%">:</td>
                        <td width="20%%">{{ $data->user->name or '' }}</td>
                        <td width="64%">{{$data->comments or ''}}</td>
                      </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 form-group">
              {{Form::label('Comments',null,array('class' => 'control-label'))}}
              {{Form::textarea('comments',null,array('class' => 'form-control max-length','rows'=>2,'maxlength'=>'100'))}}
        </div>
        <div class="col-md-12 form-group">
            <button type="submit" class="btn btn-success" id="return" onclick="$('#requisition_status').val('return')">Return</button>
            <button type="submit" class="btn btn-success" id="cancel" onclick="$('#requisition_status').val('cancel')">Cancel</button>
            <button type="submit" class="btn btn-success" id="approve" onclick="$('#requisition_status').val('approve')">Approve</button> 
            <button type="button" class="btn btn-danger" id="btnclose" data-dismiss="modal">Close</button>           
        </div>
    </div>
</form>



