@extends('layouts.admin')
@section('title', 'Add Product Sub Category')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Product Sub Category</a></li>
            <li><a>Add</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">

    <div class="col-sm-12">
        <h4 class="section-subtitle"><b>Product Sub Category Add</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('subcategories.index','<i class="fa fa-list"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
				{{ Form::model(request()->old(),array('route' => array('subcategories.store'),'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}

					<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
						{{Form::label('Product Sub Category:',null,array('class' => 'control-label col-sm-2 require'))}}
						<div class="col-md-6">
			                {{Form::text('name',null,array('class' => 'form-control'))}}
			                {!! $errors->first('title', '<p class="text-danger">:message</p>' ) !!}
						</div>
					</div>

                    <div class="form-group">
                        {{Form::label('Product Category:',null,array('class' => 'control-label col-sm-2'))}}
                        <div class="col-md-6">
                            {{Form::select('category_id',[''=>'Please Select Product Category']+$categories->toArray(),null,array('class' => 'form-control'))}}
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