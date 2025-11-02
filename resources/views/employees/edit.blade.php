@extends('layouts.admin')
@section('title', 'Update Employee')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Employee</a></li>
            <li><a>Update</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">
    <div class="col-sm-12">
        <h4 class="section-subtitle"><b>Update Employee</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('employees.index','<i class="fa fa-list"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
                {{ Form::model($employees,array('route' => array('employees.update',$employees->id),'method' => 'PUT','enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}

                   <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        {{Form::label('name:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('name',null,array('class' => 'form-control'))}}
                            {!! $errors->first('name', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('short_name') ? ' has-error' : '' }}">
                        {{Form::label('Department Name:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                             {{Form::select('department_id',[''=>'--Please Select Department--']+$departments->toArray(),null,array('class' => 'form-control'))}}
                            
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('section_id') ? ' has-error' : '' }}">
                        {{Form::label('Section Name:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                             {{Form::select('section_id',[''=>'--Please Select Section--']+$sections->toArray(),null,array('class' => 'form-control'))}}
                            
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('designation_id') ? ' has-error' : '' }}">
                        {{Form::label('Designation Name:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                             {{Form::select('designation_id',[''=>'--Please Select Designation--']+$designations->toArray(),null,array('class' => 'form-control'))}}
                            
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('region_id') ? ' has-error' : '' }}">
                        {{Form::label('Region :',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                             {{Form::select('region_id',[''=>'--Please Select Office Location--']+$Regions->toArray(),null,array('class' => 'form-control'))}}
                            
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('depot_id') ? ' has-error' : '' }}">
                        {{Form::label('Depot :',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                             {{Form::select('depot_id',[''=>'--Please Select Depot--']+$Depots->toArray(),null,array('class' => 'form-control'))}}
                            
                        </div>
                    </div>

                    <div class="form-group">
                        {{Form::label('status:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::select('status',config('myconfig.status'),null,array('class' => 'form-control'))}}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-2">
                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection

