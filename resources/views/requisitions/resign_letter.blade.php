@extends('layouts.admin')
@section('title', 'Upload Resign Letter')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Upload </a></li>
            <li><a>Resign Letter</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInRight">
    <div class="col-sm-12">
        <h4 class="section-subtitle"><b>Resign Letter</b></h4>
        <span class="pull-right">
        	{!! Html::decode(link_to_route('requisitions.inactive','<i class="fa fa-list"></i>',['approved'],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
    			<div class="widget-list list-left-element list-sm">
    				<ul class="dashboard">
    					<li>
    						<div class="left-element">MDR Name :</div>
    						 <div class="text"></div>
    					</li>
    					<li>
    						<div class="left-element">Distributor Name :</div>
    						 <div class="text"></div>
    					</li>
                        <li>
                            <div class="left-element">Region Name :</div>
                             <div class="text"></div>
                        </li>
                        <li>
                            <div class="left-element">Depot Name :</div>
                             <div class="text"></div>
                        </li>
                        <li>
                            <div class="left-element">Rocket Number :</div>
                             <div class="text"></div>
                        </li>
                        <li>
                            <div class="left-element">Effective Date :</div>
                             <div class="text"></div>
                        </li>
    				</ul>
    			</div>

				
					{{-- shop's file upload--}}
                @php
                    $fileArr = [
                        'proprietor_picture'=> 'Proprietor Picture',
                        'trade_license_copy'=> 'Trade License/ Deed/ Utility Copy',
                        'nid_copy'=> 'NID/Birth Certificate Copy',
                    ];
                @endphp
					<div class="form-group">
                        <div class="col-md-6">
                            
                        </div>
                        <div class="col-md-2 preview-div">
                             
                                <a href="" target="_blank"></a>
                              
                            
                        </div>
                    </div>
					
                
            {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
<style>
    .left-element{min-width: 160px !important;font-weight: bold;text-align: right;padding-right: 10px}
</style>
@stop
