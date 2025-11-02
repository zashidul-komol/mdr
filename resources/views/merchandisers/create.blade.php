@extends('layouts.admin')
@section('title', 'Apply Application')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Application</a></li>
            <li><a>Apply</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">
    <div class="col-sm-12">
        <h4 align="center" class="section-subtitle"><b>Application for Merchandiser </b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('dashboards.index','<i >Back</i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
               
                {{ Form::model(request()->old(),array('route' => array('merchandisers.store',$users[0]['id']),'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}

                {{ csrf_field() }}
                
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="inputName" class="col-sm-2 require">Name of Applicant:</label>
                        <div class="col-md-5">
                            {{Form::text('name',null,array('class' => 'form-control'))}}
                            {!! $errors->first('name', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="inputName" class="col-sm-2 require">Applicant Father's Name:</label>
                        <div class="col-md-5">
                            {{Form::text('applicant_fathers_name',null,array('class' => 'form-control'))}}
                            {!! $errors->first('applicant_fathers_name', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="inputName" class="col-sm-2 ">Applicant Address:</label>
                        <div class="col-md-5">
                            {{Form::text('applicant_address',null,array('class' => 'form-control'))}}
                            {!! $errors->first('applicant_address', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="inputName" class="col-sm-2 require">Applicant Rocket No:</label>
                        <div class="col-md-5">
                            {{Form::text('applicant_mobile',null,array('class' => 'form-control'))}}
                            {!! $errors->first('applicant_mobile', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="inputName" class="col-sm-2 ">Applicant Email:</label>
                        <div class="col-md-5">
                            {{Form::text('email',null,array('class' => 'form-control'))}}
                            {!! $errors->first('email', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="inputName" class="col-sm-2 ">Applicant NID:</label>
                        <div class="col-md-5">
                            {{Form::text('nid',null,array('class' => 'form-control'))}}
                            {!! $errors->first('nid', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="inputName" class="col-sm-2 require">Date of Birth</label>
                        <div class="col-md-5">
                            <div class="input-group">
                              <span class="input-group-addon x-primary"><i class="fa fa-calendar"></i></span>
                                {{Form::text('date_of_birth',null,array('class' => 'form-control datepicker' ))}}
                            </div>
                        </div> 
                        
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 require">Height</label>
                        <div class="col-md-2">
                            {{Form::text('height_feet',null,array('class' => 'form-control'))}}
                             {!! $errors->first('height_feet', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                        <label for="inputName" class="col-sm-1 require">feet</label>
                        <div class="col-md-2">
                            <div class="input-group">
                               {{Form::text('height_inch',null,array('class' => 'form-control'))}}
                          {!! $errors->first('height_inch', '<p class="text-danger">:message</p>' ) !!} 
                               
                            </div>
                        </div><b>inch</b>               
                    </div>
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="inputName" class="col-sm-2 require">Applicant Highest Education :</label>
                        <div class="col-md-5">
                            {{Form::text('education',null,array('class' => 'form-control'))}}
                            {!! $errors->first('education', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 require">Applicant Picture:</label>
                        <div class="col-md-5">
                            <div class="input-group">
                              <input type="file" name="applicant_image" > 
                              {!! $errors->first('applicant_image', '<p class="text-danger">:message</p>' ) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 require">CV / BIODATA:</label>
                        <div class="col-md-5">
                            <div class="input-group">
                              <input type="file" name="applicant_cv" > 
                               {!! $errors->first('applicant_cv', '<p class="text-danger">:message</p>' ) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 ">Certificate:</label>
                        <div class="col-md-5">
                            <div class="input-group">
                              <input type="file" name="certificate" > 
                               {!! $errors->first('certificate', '<p class="text-danger">:message</p>' ) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="inputName" class="col-sm-2 require">Remarks :</label>
                        <div class="col-md-5">
                            {{Form::textarea('remarks',null,array('class' => 'form-control max-length','rows' => 2, 'cols' => 2,'maxlength'=>'250'))}}
                            {!! $errors->first('remarks', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Propose for Recruitment
                            </button>
                        </div>
                    </div>
                {{ Form::close() }}
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


