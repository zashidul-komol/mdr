
    <div class="row">
        <div class="panel">
            <div class="panel-content">
                <div class="table-responsive">
                    <table id="datatable" class="data-table table table-striped nowrap table-hover" cellspacing="2" width="100%">
                        <thead>
                      <tr>
                        <th><strong style="font-size: 18px;">{{$Requisitor_name[0]->name}}</strong></th>
                                                  
                      </tr>
                      
                    </thead>
                    <tbody>
                        @foreach ($reportingTo_name as $key=>$data)
                      <tr>
                        <th><img src="{{asset('images/downArrow.png')}}"></th>
                      </tr>
                      <tr>
                        <th ><strong style="font-size: 18px;">{{$reportingTo_name[$key][0]}}</strong></th>
                      </tr>
                       
                        @endforeach
                    </tbody>
                    
                  </table>
                  
                </div>
            </div>
        </div>
        
    </div>



