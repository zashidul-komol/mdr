@extends('layouts.admin')
@section('title', 'Update MDR Information')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">MDR</a></li>
            <li><a>Update</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">
    <div class="col-sm-12">
        <h4 align="center" class="section-subtitle"><b>Update MDR Information </b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('requisitions.activelist','<i >Back</i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">

              <!-- Blank Page Start Here -->
              <div class="active tab-pane" id="personal">
                  {{ Form::model($ApplicationDetails[0],array('route' => array('requisitions.update',$ApplicationDetails[0]->id),'method' => 'PUT','enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}
                      <div class="form-group">
                          <label for="inputName" class="col-sm-2 ">MDR Name</label>

                          <div class="col-sm-4">
                            {{Form::text('applicant_name',null,array('class' => 'form-control' , 'readonly' => 'true'))}}
                              {!! $errors->first('applicant_name', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                          <label for="inputName" class="col-sm-2 ">Region Name</label>
                          @php
                              $regionName = '';
                              if(!empty($ApplicationDetails[0]->regions)){
                              $regionName = $ApplicationDetails[0]->regions->name;
                            }
                            @endphp
                          <div class="col-sm-4">
                            {{Form::select('region_id',[''=>'--Please Select Region--']+$RegionNameQry->toArray(),null,array('class' => 'form-control select2'))}}
                            
                          </div>
                      </div>

                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 ">Depot Name</label>
                          @php
                              $DepotNameF = '';
                              if(!empty($ApplicationDetails[0]->depots)){
                              $DepotNameF = $ApplicationDetails[0]->depots->name;
                            }
                            @endphp
                          <div class="col-sm-4">
                            {{Form::select('depot_id',[''=>'--Please Select Depot--']+$DepotName->toArray(),null,array('class' => 'form-control select2'))}}

                          </div>
                          
                          <label for="inputName" class="col-sm-2 ">Distributor Name</label>
                          @php
                              $DBName = '';
                              if(!empty($ApplicationDetails[0]->distributors)){
                              $DBName = $ApplicationDetails[0]->distributors->distributorName;
                            }
                            @endphp
                          <div class="col-sm-4">
                            {{Form::select('distributor_id',[''=>'--Please Select Distributor--']+$distributorsUser->toArray(),null,array('class' => 'form-control select2'))}}
                            
                          </div>
                      </div>
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 ">Rocket No.</label>
                          <div class="col-sm-4">
                            {{Form::text('applicant_mobile',null,array('class' => 'form-control'))}}
                              {!! $errors->first('applicant_mobile', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                          <label for="inputName" class="col-sm-2 ">Employee Name</label>
                          @php
                              $EmplyeeName = '';
                              if(!empty($ApplicationDetails[0]->distributors)){
                              $EmplyeeName = $ApplicationDetails[0]->distributors->distributorName;
                            }
                            @endphp
                          <div class="col-sm-4">
                            {{Form::select('employee_id',[''=>'--Please Select Employee--']+$EmployeeName->toArray(),null,array('class' => 'form-control select2'))}}
                            
                          </div>
                          
                          
                      </div>
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 ">Gross Salary</label>
                          <div class="col-sm-4">
                            {{Form::text('basic_salary',null,array('class' => 'form-control'))}}
                              {!! $errors->first('basic_salary', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                          
                          
                          
                      </div>
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 ">Status</label>
                        <div class="col-sm-4">
                            {{Form::select('status',config('myconfig.status'),null,array('class' => 'form-control'))}}
                        </div>
                        <label for="inputName" class="col-sm-2 ">Application No.</label>
                          <div class="col-sm-4">
                            {{Form::text('application_id',null,array('class' => 'form-control', 'readonly' => 'true'))}}
                              {!! $errors->first('application_id', '<p class="text-danger">:message</p>' ) !!}
                          </div>
                    </div>
                    <div class="form-group">
                      <label for="inputName" class="col-sm-2 ">Inactive Reason</label>
                          <div class="col-sm-4">
                            {{Form::textarea('inactivereason',null,array('class' => 'form-control max-length','rows' => 2, 'cols' => 2,'maxlength'=>'150'))}}
                              {!! $errors->first('inactivereason', '<p class="text-danger">:message</p>' ) !!}
                          </div>

                       <label for="inputName" class="col-sm-2 require">Inactive Date</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                              <span class="input-group-addon x-primary"><i class="fa fa-calendar"></i></span>
                                {{Form::text('inactiveDate',null,array('class' => 'form-control datepicker' ))}}
                            </div>
                        </div>


                        
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary"> Update</button>
                        </div>
                    </div>
                 {{ Form::close() }}
                  <!-- /.form-horizontal -->
              </div>

              <!-- Blank Page End Here --> 
            </div>
        </div>
    </div>
</div>
@endsection
@component('common_pages.selectize')
    <script src="{{ asset('vendor/bootstrap_date-picker/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        var todayDate = new Date();
        var maxDate = new Date();
        todayDate.setDate(todayDate.getDate()-10);
         $('.datepicker3').datepicker({ 
            format: "yyyy-mm-dd",
            todayHighlight: true,
            autoclose:true,
             startDate : todayDate,
             endDate : maxDate
        
        });

    </script>
    <script>
         $('.datepicker').datepicker({ format: "yyyy-mm-dd",todayHighlight: true,autoclose:true});

    </script>
    @slot('css')
     <!--Date picker-->
     <link rel="stylesheet" href="{{ asset('vendor/bootstrap_date-picker/css/bootstrap-datepicker3.min.css') }}">
    @endslot
@endcomponent

