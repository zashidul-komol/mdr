@extends('layouts.admin')
@section('title', 'Vehicles Lists')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Vehicles</a></li>
            <li><a>Lists</a></li>
        </ul>
    </div>
</div>


<div class="row animated fadeInRight">
    <div class="col-sm-12">
       <h4 class="section-subtitle"><b>Vehicle Lists</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('vehicles.download','<i class="fa fa-download" aria-hidden="true"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}

            {!! Html::decode(link_to_route('vehicles.create','<i class="fa fa-plus"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
				      <div class="table-responsive">
                <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>SI NO.</th>
                        <th>Vehicles No.</th>
                        <th>Registration No.</th>
                        <th>Type/Location</th>
                        <th>Model/Brand</th>
                        <th>Year</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php ($i=1)
                        @foreach ($vehicles as $data)
                      <tr>
                        <td>{{$i}}</td>
                      	<td>{{$data->name  ??  ''}}</td>
                        <td>{{$data->regNo  ??  ''}}</td>
                        <td>{{$data->type  ??  ''}}</td>
                        <td>{{$data->model  ??  ''}}</td>
                        <td>{{$data->year  ??  ''}}</td>
                        <td>{{$data->capacity  ??  ''}}</td>
                        <td>{{config('myconfig.status')[$data->status] }}</td>
                        <td>
                          {!!  Html::decode(link_to_route('vehicles.edit', '<span aria-hidden="true" class="fa fa-edit fa-x"></span>', array($data->id)))!!}

                          <form action="{{ route('vehicles.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit">Delete</button>
                              </form>
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

