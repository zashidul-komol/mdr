
@extends('layouts.admin')
@section('title', 'Add Employee')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Employee</a></li>
            <li><a>Add</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">

    <div class="col-sm-12">
        <h4 class="section-subtitle"><b>Employee Add</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('employees.index','<i class="fa fa-list"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
				{{ Form::model(request()->old(),array('route' => array('employees.store'),'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        {{Form::label('name:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('name',null,array('class' => 'form-control'))}}
                            {!! $errors->first('title', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        {{Form::label('Polar ID:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('polar_id',null,array('class' => 'form-control'))}}
                            {!! $errors->first('polar_id', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('Department:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6"> 
                            {{Form::select('department_id',[''=>'Please Select Department']+$departments->toArray(),null,array('class' => 'form-control', 'id'=>'department_id'))}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('Section:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::select('section_id',[''=>'Please Select Section']+$sections->toArray(),null,array('class' => 'form-control', 'id'=>'section_id'))}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('Designation:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::select('designation_id',[''=>'Please Select Designation']+$designations->toArray(),null,array('class' => 'form-control select2'))}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('Office Location:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::select('officelocation_id',[''=>'Please Select Office Location']+$officelocations->toArray(),null,array('class' => 'form-control select2'))}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('Region:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6"> 
                            {{Form::select('region_id',[''=>'Please Select Region']+$regions->toArray(),null,array('class' => 'form-control', 'id'=>'region_id'))}}
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        {{Form::label('Mobile:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('mobile',null,array('class' => 'form-control'))}}
                            {!! $errors->first('title', '<p class="text-danger">:message</p>' ) !!}
                        </div>
                    </div>
					<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {{Form::label('Email:',null,array('class' => 'control-label col-sm-2 require'))}}
                        <div class="col-md-6">
                            {{Form::text('email',null,array('class' => 'form-control'))}}
                            {!! $errors->first('email', '<p class="text-danger">:message</p>' ) !!}
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
});
</script>
@endsection

