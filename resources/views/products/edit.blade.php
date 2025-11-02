@extends('layouts.admin')
@section('title', 'Update Product')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Product</a></li>
            <li><a>Update</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">
    <div class="col-sm-12">
        <h4 class="section-subtitle"><b>Product Update</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('products.index','<i class="fa fa-list"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
                {{ Form::model($products,array('route' => array('products.update',$products->id),'method' => 'PUT','enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}

                   <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {{Form::label('Name:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('name',null,array('class' => 'form-control'))}}
                            {!! $errors->first('name', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                     <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                        {{Form::label('Category Name:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                             {{Form::select('category_id',[''=>'--Please Select Designation--']+$categories->toArray(),null,array('class' => 'form-control'))}}
                            
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('subcategory_id') ? ' has-error' : '' }}">
                        {{Form::label('Sub Category :',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                             {{Form::select('subcategory_id',[''=>'--Please Select Office Location--']+$subcategories->toArray(),null,array('class' => 'form-control'))}}
                            
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
                    <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                        {{Form::label('code:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('code',null,array('class' => 'form-control'))}}
                            {!! $errors->first('code', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('specification') ? ' has-error' : '' }}">
                        {{Form::label('specification:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('specification',null,array('class' => 'form-control'))}}
                            {!! $errors->first('specification', '<p class="text-danger">:message</p>' ) !!}
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

