@extends('layouts.admin')
@section('title', 'Section Lists')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Section</a></li>
            <li><a>Lists</a></li>
        </ul>
    </div>
</div>


<div class="row animated fadeInRight">
    <div class="col-sm-12">
       <h4 class="section-subtitle"><b>Section Lists</b></h4>
        <span class="pull-right">
            {!! Html::decode(link_to_route('sections.download','<i class="fa fa-download" aria-hidden="true"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}

            {!! Html::decode(link_to_route('sections.create','<i class="fa fa-plus"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
				      <div class="table-responsive">
                <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>SI NO.</th>
                        <th>Department Name</th>
                        <th>Section Name</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php ($i=1)
                        @foreach ($sections as $data)
                      <tr>
                        <td>{{$i}}</td>
                      	<td>{{$data->department->name  ??  ''}}</td>
                        <td>{{$data->name  ??  ''}}</td>
                        <td>{{config('myconfig.status')[$data->status] }}</td>
                        <td>
                          {!!  Html::decode(link_to_route('sections.edit', '<span aria-hidden="true" class="fa fa-edit fa-x"></span>', array($data->id)))!!}

                         <form action="{{ route('sections.destroy', $data->id) }}" method="POST" 
      id="deleteForm" style="display: inline;">
    @csrf
    @method('DELETE')
</form>

<i aria-hidden="true" class="fa fa-remove fa-x" onclick="if(confirm('Are you sure you want to delete this section?')) document.getElementById('deleteForm').submit();"></i>
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

