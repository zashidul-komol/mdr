@extends('layouts.admin')
@section('title', 'Add Requisition')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Requisition</a></li>
            <li><a>Add</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">
    <div class="col-sm-12">
        <h4 class="section-subtitle"><b>Requisition Add</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('requisitions.index','<i class="fa fa-list"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>

        <div class="panel">
            <div class="panel-content">
               
                {{ Form::model(request()->old(),array('route' => array('requisitions.store',$users[0]['id']),'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}
                <legend style="color:#2C89B5; cursor:pointer;">Enter Requisition Information</legend>

                    <table width="92%" border="0" align="left">
                        <tr>
                          <th width="13%" height="36">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name</th>
                          <th width="2%">: </th>
                          <th width="34%">{{Form::text('name',$users[0]->name,array('class' => 'form-control', 'readonly' => 'true'))}}
                              {!! $errors->first('name', '<p class="text-danger">:message</p>' ) !!}</th>
                          <th width="1%">&nbsp;</th>
                          <th width="16%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Designation</th>
                          <th width="2%">:</th>
                          <th width="33%">{{Form::text('designation_id',$users[0]->designation->title,array('class' => 'form-control', 'readonly' => 'true'))}}
                            {!! $errors->first('designation_id', '<p class="text-danger">:message</p>' ) !!}
                              
                        </tr>
                        <tr>
                          <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Department</th>
                          <th>:</th>
                          <th>{{Form::text('department_id',$users[0]->department->name,array('class' => 'form-control', 'readonly' => 'true'))}}
                              {!! $errors->first('department_id', '<p class="text-danger">:message</p>' ) !!}
                          </th>
                          <th>&nbsp;</th>
                          <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Section</th>
                          <th>:</th>
                          <th style="width: 30%;">{{Form::text('section_id',$users[0]->section->name,array('class' => 'form-control', 'readonly' => 'true'))}}
                              {!! $errors->first('section_id', '<p class="text-danger">:message</p>' ) !!}
                          </th>
                        </tr>
                        <tr>
                          <th height="32">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Req. Date</th>
                          <th>:</th>
                          <th>{{Form::text('date', $CurrentDate,array('class' => 'form-control datepicker', 'readonly' => 'true'))}}
                              {!! $errors->first('date', '<p class="text-danger">:message</p>' ) !!}</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Requisition No</th>
                          <th>: </th>
                          <th>{{Form::text('maxrequisition_no', $NowMaxNo,array('class' => 'form-control', 'readonly' => 'true'))}}
                              {!! $errors->first('maxrequisition_no', '<p class="text-danger">:message</p>' ) !!}</th>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                  <div class="from-group">
                        @if (count($errors->get('details.*'))>0)
                            <div class="alert alert-danger" style="width:64%;padding: 5px;    margin-bottom: 10px;margin-left: 38px;">
                                <ul>
                                    <li>Product Name cann't be blank</li>
                                    
                                </ul>
                            </div>
                        @endif
                        @if(request()->old('details'))
                            <requisitions :products="{{ $products }}" :sizes="{{ $products }}" :details="{{ collect(request()->old('details')) }}"/>
                        @else
                            <requisitions :products="{{ $products }}" :sizes="{{ $products }}" :details="[]"/>
                        @endif

                    </div>
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Submit Requisition
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
         $('.datepicker').datepicker({ format: "yyyy-mm-dd",todayHighlight: true,autoclose:true});

    </script>
    @slot('css')
     <!--Date picker-->
     <link rel="stylesheet" href="{{ asset('vendor/bootstrap_date-picker/css/bootstrap-datepicker3.min.css') }}">
    @endslot
@endcomponent


