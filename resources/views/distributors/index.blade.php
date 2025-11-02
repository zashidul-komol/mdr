@extends('layouts.admin')
@section('title', 'Distributor Lists')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Distributor</a></li>
            <li><a>Lists</a></li>
        </ul>
    </div>
</div>


<div class="row animated fadeInRight">
    <div class="col-sm-12">
       <h4 class="section-subtitle"><b>Distributor Lists</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('distributors.create','<i class="fa fa-plus"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
				      <div class="table-responsive">
                <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>SI NO.</th>
                        <th>Distributor Name</th>
                        <th>Distributor Code</th>
                        <th>Region</th>
                        <th>Depot</th>
                        <th>Area Name</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php ($i=1)
                        @foreach ($DistributorList as $data)
                      <tr>
                        <td>{{$i}}</td>
                      	<td>{{$data->distributorName or ''}}</td>
                        <td>{{$data->dbcode or ''}}</td>
                        <td>{{$data->region->name or ''}}</td>
                        <td>{{$data->depot->name or ''}}</td>
                        <td>{{$data->areaname or ''}}</td>
                        <td>{{config('myconfig.status')[$data->status] }}</td>
                        <td>
                          {!!  Html::decode(link_to_route('distributors.edit', '<span aria-hidden="true" class="fa fa-edit fa-x"></span>', array($data->id)))!!}
                          {!! Form::delete(route('distributors.destroy',array($data->id))) !!}
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

