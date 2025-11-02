@extends('layouts.admin')
@section('title', 'Update Machine')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Machine</a></li>
            <li><a>Update</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">
    <div class="col-sm-12">
        <h4 class="section-subtitle"><b>Machine Lists</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('machines.index','<i class="fa fa-list"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
                {{ Form::model($machines,array('route' => array('machines.update',$machines->id),'method' => 'PUT','enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}

                   <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        {{Form::label('Machine Name:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('name',null,array('class' => 'form-control'))}}
                            {!! $errors->first('name', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        {{Form::label('Description:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('description',null,array('class' => 'form-control'))}}
                            {!! $errors->first('description', '<p class="text-danger">:message</p>' ) !!}
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

