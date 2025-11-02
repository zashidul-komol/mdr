<form id="updateReqEntry" style="position: relative;overflow: hidden;" 
    action="{{ route('requisitions.updateRequisition') }}" method="post">
    <input type="hidden" name="application_id" value="{{$application_id}}">
    <input type="hidden" name="application_status" id="application_status" value="">
  {{ csrf_field() }} 
    <div class="row animated fadeInRight">
        <div class="col-md-3">
            <!--CONTACT INFO-->
            <div class="panel bg-scale-0 b-primary bt-sm mt-xl">
                <div class="panel-content">
                    <div class="box box-primary">
                      <div class="box-body box-profile">
                        
                        @php
                          $avatar = '';
                          if(!empty($ApplicationDetails[0])){
                          $avatar = $ApplicationDetails[0]->applicant_image;
                        }
                        @endphp
                        @if($avatar)
                          <img class="profile-user-img img-responsive img-circle" src="{{ asset('storage/applicantImages/'.$avatar) }}" alt="User profile picture">
                        @else
                          <img class="profile-user-img img-responsive img-circle" src="{{ asset('storage/images/avatar/avatar_user.jpg') }}" />
                        @endif
                        {!! $errors->first('avatar', '<p class="text-danger">:message</p>' ) !!}

                        <h5 class="profile-username text-center">{{$employees[0]->name or ''}}</h5>

                        <p class="text-muted text-center">{{$employees[0]->designation->title or ''}}</p>

                      </div>
                <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            
            <div class="panel">
                <div class="panel-content">
                    
                    <!-- Blank Page Start Here -->
                    <div class="active tab-pane" id="personal">
                      
                            <div class="form-group">
                              <label for="inputName" class="col-sm-3 ">Full Name</label>
                              <div class="col-sm-9">
                               {{Form::text('applicant_name',$ApplicationDetails[0]->applicant_name,array('class' => 'form-control', 'readonly' => 'true'))}}
                                  {!! $errors->first('applicant_name', '<p class="text-danger">:message</p>' ) !!}
                              </div>
                              
                            </div>
                            <div class="form-group">
                              <label for="inputName" class="col-sm-3 ">Father's Name</label>
                              <div class="col-sm-9">
                               {{Form::text('applicant_fathers_name',$ApplicationDetails[0]->applicant_fathers_name,array('class' => 'form-control', 'readonly' => 'true'))}}
                                  {!! $errors->first('applicant_fathers_name', '<p class="text-danger">:message</p>' ) !!}
                              </div>
                              
                            </div>
                            <div class="form-group">
                              <label for="inputName" class="col-md-3 ">Rocket No.</label>
                              <div class="col-sm-9">
                                {{Form::text('applicant_mobile',$ApplicationDetails[0]->applicant_mobile,array('class' => 'form-control', 'readonly' => 'true'))}}
                                  {!! $errors->first('applicant_mobile', '<p class="text-danger">:message</p>' ) !!}
                              </div>
                              
                            </div>
                                                  
                            <div class="form-group">
                                <label for="inputName" class="col-sm-3 ">Age</label>
                                <div class="col-sm-9">
                                  {{Form::text('applicant_mobile',$ApploicantAge,array('class' => 'form-control', 'readonly' => 'true'))}}  
                                  {!! $errors->first('date_of_birth', '<p class="text-danger">:message</p>' ) !!} 
                                </div>               
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-3 ">Height</label>
                                <div class="col-sm-9">
                                  {{Form::text('Height',$Height,array('class' => 'form-control', 'readonly' => 'true'))}}  
                                  {!! $errors->first('Height', '<p class="text-danger">:message</p>' ) !!} 
                                </div>               
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-3 ">Email</label>
                                <div class="col-sm-9">
                                   {{Form::text('applicant_email',$ApplicationDetails[0]->applicant_email,array('class' => 'form-control', 'readonly' => 'true'))}}
                                  {!! $errors->first('applicant_email', '<p class="text-danger">:message</p>' ) !!} 
                                </div>               
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-3 ">NID</label>
                                <div class="col-sm-9">
                                   {{Form::text('nid',$ApplicationDetails[0]->nid,array('class' => 'form-control', 'readonly' => 'true'))}}
                                  {!! $errors->first('nid', '<p class="text-danger">:message</p>' ) !!} 
                                </div>               
                            </div>
                            <div class="form-group">                          
                              <label for="inputName" class="col-sm-3 ">Highest Education</label>
                              <div class="col-sm-9">
                                 {{Form::text('applicant_education',$ApplicationDetails[0]->applicant_education,array('class' => 'form-control', 'readonly' => 'true'))}}
                                  {!! $errors->first('applicant_education', '<p class="text-danger">:message</p>' ) !!}
                              </div>
                            </div>
                            <div class="form-group">                          
                              <label for="inputName" class="col-sm-3 ">Address</label>
                              <div class="col-sm-9">
                                 {{Form::text('applicant_address',$ApplicationDetails[0]->applicant_address,array('class' => 'form-control', 'readonly' => 'true'))}}
                                  {!! $errors->first('applicant_address', '<p class="text-danger">:message</p>' ) !!}
                              </div>
                            </div>
                            <div class="form-group">                          
                              <label for="inputName" class="col-sm-3 ">Market Activity</label>
                              <div class="col-sm-9">
                                 {{Form::text('MarketScore',$MarketScore,array('class' => 'form-control', 'readonly' => 'true'))}}  
                                  {!! $errors->first('MarketScore', '<p class="text-danger">:message</p>' ) !!}
                              </div>
                            </div>
                            <div class="form-group">                          
                              <label for="inputName" class="col-sm-3 ">Rating</label>
                              <div class="col-sm-9">
                                 {{Form::text('rating',$ApplicationDetails[0]->rating,array('class' => 'form-control', 'readonly' => 'true'))}}
                                  {!! $errors->first('rating', '<p class="text-danger">:message</p>' ) !!}
                              </div>
                            </div>
                            <div class="form-group">                          
                              <label for="inputName" class="col-sm-3 ">Distributor</label>
                              <div class="col-sm-9">
                                 {{Form::text('distributor_id',$DistributorName,array('class' => 'form-control', 'readonly' => 'true'))}}
                                  {!! $errors->first('distributor_id', '<p class="text-danger">:message</p>' ) !!}
                              </div>
                            </div>
                            <div class="form-group">                          
                              <label for="inputName" class="col-sm-3 ">Effective Date :</label>
                              <div class="col-sm-9">
                                 {{Form::text('effectivedate',$ApplicationDetails[0]->effectivedate,array('class' => 'form-control', 'readonly' => 'true'))}}
                                  {!! $errors->first('effectivedate', '<p class="text-danger">:message</p>' ) !!}
                              </div>
                            </div>
                            <div class="form-group">
                            <label for="inputName" class="col-sm-3 ">Certificate / CV</label>
                                <div class="col-sm-9">
                                    <a href="{{ asset('storage/Certificate/'.$ApplicationDetails[0]->certificate) }}" target="_blank">{{ $ApplicationDetails[0]->certificate }}</a>
                                </div>
                            </div>
                            <div class="form-group" class="col-md-4 ">
                            <label for="inputName" ></label>
                                <div class="col-sm-3">
                                    <a href="{{ asset('storage/applicantCV/'.$ApplicationDetails[0]->applicant_cv) }}" target="_blank">{{ $ApplicationDetails[0]->applicant_cv }}</a>
                                </div>
                            </div>
                            
                        <!-- /.form-horizontal -->
                    </div>

                    <!-- Blank Page End Here --> 
                </div>
            </div>
            
        </div>
        
    </div>

    <div class="panel">
        <div class="panel-content">
            <div class="table-responsive">
                <table id="datatable" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                    <tbody>
                        @foreach ($ApplicationLogs as $key=>$data)
                      <tr>
                        <td width="24%">{{$data->employee->name}}</td>
                        <td width="1%">:</td>
                        <td width="20%">{{$data->updated_at or ''}}</td>
                        <td width="55%">{{$data->remarks or ''}}</td>
                      </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 ">Comments:</label>
                    <div class="col-sm-10 form-group">
                        {{Form::textarea('remarks',null,array('class' => 'form-control max-length','rows'=>2,'maxlength'=>'250'))}}
                    </div>
                </div>
                
            </div>
            <div class="col-md-12 form-group">
                <button type="submit" class="btn btn-success" id="return" onclick="$('#application_status').val('return')">Return</button>
                <button type="submit" class="btn btn-success" id="cancel" onclick="$('#application_status').val('cancel')">Cancel</button>
                <button type="submit" class="btn btn-success" id="approve" onclick="$('#application_status').val('approve')">Approve</button> 
                <button type="button" class="btn btn-danger" id="btnclose" data-dismiss="modal">Close</button>           
            </div>
            
        </div>
    </div>
</form>
    
                    
