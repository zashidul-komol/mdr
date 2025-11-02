@extends('layouts.admin')
@section('title', 'Add Reporting Sequence')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">TA/DA Reporting Sequence</a></li>
            <li><a>Add</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">
    <div class="col-sm-12">
        <h4 class="section-subtitle"><b>Add TA/DA Reporting Sequence</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('tada_reportingsequences.index','<i class="fa fa-list"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
                {{ Form::model(request()->old(),array('route' => array('tada_reportingsequences.store'),'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }}
                    <div class="form-group">
                        {{Form::label('For:',null,array('class' => 'control-label col-sm-1 require'))}}
                        <div class="col-md-5">
                            {{Form::select('user_id',[''=>'Please Select Employee']+$users->toArray(),null,array('class' => 'form-control'))}}
                        </div>
                    </div>
                    <div class="from-group">
                        @if (count($errors->get('details.*'))>0)
                            <div class="alert alert-danger" style="width:64%;padding: 5px;    margin-bottom: 10px;margin-left: 38px;">
                                <ul>
                                    <li>Size,Brand & Quantity cann't be blank</li>
                                    <li>Unit price should be a number or blank</li>
                                </ul>
                            </div>
                        @endif
                        @if(request()->old('details'))
                            <stockdetails :brands="{{ $users }}" :details="{{ collect(request()->old('details')) }}"/>
                        @else
                            <stockdetails :brands="{{ $users }}" :details="[]"/>
                        @endif

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


