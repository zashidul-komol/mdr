@extends('layouts.admin')
@section('title', 'Add Measurement')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Measurement</a></li>
            <li><a>Add</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">

    <div class="col-sm-12">
        <h4 class="section-subtitle"><b>Unit of Measurement Add</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('measurements.index','<i class="fa fa-list"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
				{{ Form::model(request()->old(),array('route' => array('measurements.store'),'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}

					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {{Form::label('Name:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('name',null,array('class' => 'form-control'))}}
                            {!! $errors->first('name', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('shortname') ? ' has-error' : '' }}">
						{{Form::label('Short Name:',null,array('class' => 'control-label col-sm-2 require'))}}
						<div class="col-md-6">
			                {{Form::text('shortname',null,array('class' => 'form-control'))}}
			                {!! $errors->first('shortname', '<p class="text-danger">:message</p>' ) !!}
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
                                ADD
                            </button>
                        </div>
                    </div>
				{{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection