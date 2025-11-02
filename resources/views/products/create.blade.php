@extends('layouts.admin')
@section('title', 'Add Product')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Product </a></li>
            <li><a>Add</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">

    <div class="col-sm-12">
        <h4 class="section-subtitle"><b>Product Add</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('products.index','<i class="fa fa-list"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
				{{ Form::model(request()->old(),array('route' => array('products.store'),'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}

					<div class="form-group">
                        {{Form::label('Department:',null,array('class' => 'control-label col-sm-2'))}}
                        <div class="col-md-6">
                            {{Form::select('department_id',[''=>'Please Select Department']+$departments->toArray(),null,array('class' => 'form-control select2', 'id'=>'department_id'))}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('Section:',null,array('class' => 'control-label col-sm-2'))}}
                        <div class="col-md-6">
                            {{Form::select('section_id',[''=>'Please Select Section']+$sections->toArray(),null,array('class' => 'form-control select2', 'id'=>'section_id'))}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('Product Category:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::select('category_id',[''=>'Please Select Category']+$categories->toArray(),null,array('class' => 'form-control select2', 'id'=>'category_id'))}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('Product Sub Category:',null,array('class' => 'control-label col-sm-2'))}}
                        <div class="col-md-6">
                            {{Form::select('subcategory_id',[''=>'Please Select Sub Category']+$subcategories->toArray(),null,array('class' => 'form-control select2', 'id'=>'subcategory_id'))}}
                        </div>
                    </div>
                    <div class="form-group">
                                {{Form::label('Product Tag:',null,array('class' => 'control-label col-sm-2 require'))}}
                                <div class="col-md-6">
                                    {{Form::select('tags',[''=>'Please Select Product Tags']+config('myconfig')['Product_tags'],null,array('class' => 'form-control select2'))}}
                                </div>
                            </div>
                    <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                        {{Form::label('Product Code:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('code',null,array('class' => 'form-control', 'id'=>'code', 'readonly' => 'true'))}}
                            {!! $errors->first('code', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
						{{Form::label('Product Name:',null,array('class' => 'control-label col-sm-2 require'))}}
						<div class="col-md-6">
			                {{Form::text('name',null,array('class' => 'form-control'))}}
			                {!! $errors->first('name', '<p class="text-danger">:message</p>' ) !!}
						</div>
					</div>
                    <div class="form-group{{ $errors->has('specification') ? ' has-error' : '' }}">
                        {{Form::label('Specification:',null,array('class' => 'control-label col-sm-2'))}}
                        <div class="col-md-6">
                            {{Form::text('specification',null,array('class' => 'form-control'))}}
                            {!! $errors->first('specification', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('Status:',null,array('class' => 'control-label col-sm-2 require'))}}
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
@section('script')
<script>
$( document ).ready(function() {
    $( "#department_id" ).change(function() {
      let department_id = $( this ).val();
      $.ajax({
            type: 'GET',
            url:"{{ url('/') }}/get-section-by-department/"+department_id,
            data:{}
        }).done(function(response) {
             $('#section_id option').remove();

             let sec_option = '<option value="">Select Section</option>';
             $(response).each(function(k, v) {
                //console.log('k',v);
                sec_option += '<option value="'+v.id+'">'+v.name+'</option>';
              });

              $('#section_id').append(sec_option);

        }).fail(function(response) {
            console.log(response);
        });
    });

    $( "#category_id" ).change(function() {
      let category_id = $( this ).val();
      $.ajax({
            type: 'GET',
            url:"{{ url('/') }}/get-subcategorybycategory/"+category_id,
            data:{}
        }).done(function(response) {
             $('#subcategory_id option').remove();

             let cat_option = '<option value="">Select Sub Category</option>';
             $(response.subcategory).each(function(k, v) {
                //console.log('k',v);
                cat_option += '<option value="'+v.id+'">'+v.name+'</option>';
              });

              $('#subcategory_id').append(cat_option);
              $('#code').val(response.code);

        }).fail(function(response) {
            console.log(response);
        });
    });
    
});
 </script>
@endsection