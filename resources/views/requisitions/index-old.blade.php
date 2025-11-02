@extends('layouts.admin')
@section('title', 'Requisition Lists')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Requisition</a></li>
            <li><a>Lists</a></li>
        </ul>
    </div>
</div>
<div class="tabs">
    <ul class="nav nav-tabs">
        <li class="{{'active'}}"><a href="{{ route('requisitions.index') }}">New</a></li>
        <li><a href="{{ route('requisitions.index') }}">Processing</a></li>
        <li><a href="{{ route('requisitions.index') }}">Hold</a></li>
        <li><a href="{{ route('requisitions.index') }}">Approved</a></li>
    </ul>
</div>

<div class="row animated fadeInRight">
    <div class="col-sm-12">
       <h4 class="section-subtitle"><b>Requisition Lists</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('requisitions.create','<i class="fa fa-plus"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
                      <div class="table-responsive">
                <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>SI NO.</th>
                        <th>Employee Name</th>
                        <th>Department Name</th>
                        <th>Section Name</th>
                        <th>Requisition Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php ($i=1)
                        @foreach ($reportToRequisitions as $data)
                      <tr>
                        <td>{{$i}}</td>
                        <td>{{$data->user->name or ''}}</td>
                        <td>{{$data->department->name or ''}}</td>
                        <td>{{$data->section->name or ''}}</td>
                        <td>{{$data->date or ''}}</td>
                        <td>
                          {!!  Html::decode(link_to_route('requisitions.index', '<span aria-hidden="true" class="fa fa-eye fa-x"></span>', array($data->id)))!!}
                          
                        </td>
                      </tr>
                        @php ($i=$i+1)
                        @endforeach
                    </tbody>
                </table>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
@component('common_pages.data_table_script')
<script>
  $(function(){
      "use strict";
      $('.data-table').DataTable({
        "order": [], /* No ordering applied by DataTables during initialisation */
      });
  });
</script>
@endcomponent

