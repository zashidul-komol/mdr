@extends('layouts.admin')
@section('title', 'Add Distributor')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Distributor</a></li>
            <li><a>Add</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">

    <div class="col-sm-12">
        <h4 class="section-subtitle"><b>Distributor Add</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('distributors.index','<i class="fa fa-list"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
				{{ Form::model(request()->old(),array('route' => array('distributors.store'),'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}

					<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
						{{Form::label('Distributor Name:',null,array('class' => 'control-label col-sm-2 require'))}}
						<div class="col-md-6">
			                {{Form::text('distributorName',null,array('class' => 'form-control'))}}
			                {!! $errors->first('title', '<p class="text-danger">:message</p>' ) !!}
						</div>
					</div>

                    <div class="form-group{{ $errors->has('dbcode') ? ' has-error' : '' }}">
                        {{Form::label('DB Code:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('dbcode',null,array('class' => 'form-control'))}}
                            {!! $errors->first('dbcode', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('Region:',null,array('class' => 'control-label col-sm-2'))}}
                        <div class="col-md-6">
                            {{Form::select('region_id',[''=>'Please Select Region']+$regions->toArray(),null,array('class' => 'form-control'))}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('Depot:',null,array('class' => 'control-label col-sm-2'))}}
                        <div class="col-md-6">
                            {{Form::select('depot_id',[''=>'Please Select Depot']+$depots->toArray(),null,array('class' => 'form-control'))}}
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('areaname') ? ' has-error' : '' }}">
                        {{Form::label('Area:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('areaname',null,array('class' => 'form-control'))}}
                            {!! $errors->first('areaname', '<p class="text-danger">:message</p>' ) !!}
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