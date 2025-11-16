@extends('layouts.admin')
@section('title', 'Reporting Sequence Lists')
@section('content')
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-table" aria-hidden="true"></i><a href="#">Reporting Sequence</a></li>
            <li><a>Lists</a></li>
        </ul>
    </div>
</div>


<div class="row animated fadeInRight">
    <div class="col-sm-12">
       <h4 class="section-subtitle"><b>Reporting Sequence Lists</b></h4>
        <span class="pull-right">
            
            {!! Html::decode(link_to_route('reportingsequences.create','<i class="fa fa-plus"></i>',[],array('class'=>'btn btn-success btn-right-side'))) !!}
        </span>
        <div class="panel">
            <div class="panel-content">
				      <div class="table-responsive">
                <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>SI NO.</th>
                        <th>User Name</th>
                        <th>Designation</th>
                        <th>Dept. Name</th>
                        <th>Section Name</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php ($i=1)
                        @foreach ($reportingsequences as $data)
                      <tr>
                        <td>{{$i}}</td>
                      	<td>{{$data->user->name  ??  ''}}</td>
                        <td>{{$data->user->designation->title  ??  ''}}</td>
                        <td>{{$data->user->department->name  ??  ''}}</td>
                        <td>{{$data->user->section->name  ??  ''}}</td>
                        <td>                               
                            <a class="fa fa-eye fa-x" style="cursor:pointer" onclick="showModal({{ $data->user->id }})"></a>                          
                        </td>
                      </tr>
                        @php ($i=$i+1)
                        @endforeach
                    </tbody>
                </table>
              </div>
            </div>
        </div>
        <!-- Modal for problem entry start-->
        @include('common_pages.common_modal_reporting',['modalTitle'=>'Reporting Sequence'])
         <!-- Modal for problem entry end-->
    </div>
</div>
@endsection
@component('common_pages.data_table_script')
@slot('css')
    <style>
      .dropup, .dropdown{
        display: inline-block;
      }
      .dropdown-menu{
        right: 0;
        left:auto;
      }
    </style>
@endslot
<script>

  $(function(){

        "use strict";
        var table = $('.data-table').DataTable({
            //"aaSorting": [],  /* Disable initial sort Older version*/
            "order": [], /* No ordering applied by DataTables during initialisation */
            "pageLength": 25,
            "columnDefs": [ {
              "targets": 'no-sort',
              "orderable": false,
              "order": []
            } ]
        });

   // Handle click on "Select all" control
      $('#datatable-select-all').on('click', function(){
         // Get all rows with search applied
         var rows = table.rows({ 'search': 'applied' }).nodes();
         // Check/uncheck checkboxes for all rows in the table
         $('input[type="checkbox"]', rows).prop('checked', this.checked);
      });

      // Handle click on checkbox to set state of "Select all" control
      $('#datatable tbody').on('change', 'input[type="checkbox"]', function(){
         // If checkbox is not checked
         if(!this.checked){
            var el = $('#datatable-select-all').get(0);
            // If "Select all" control is checked and has 'indeterminate' property
            if(el && el.checked && ('indeterminate' in el)){
               // Set visual state of "Select all" control
               // as 'indeterminate'
               el.indeterminate = true;
            }
         }
      });
  });
</script>
@section('vuescript')
<script>
        function showModal(id,){
            laravelObj.common=id;
            var modalBody=$('#modal-body');
            modalBody.css('padding-top',0);
            modalBody.html('');
            $.ajax({
                type: 'Get',
                url:"{{ route('ajax.reporting.getReportingSequence') }}",
                data:{id:id}
            }).done(function(response) {
                 modalBody.html(response);
                 $.fn.select2.defaults.set( "theme", "bootstrap" );
                 $(".select2").select2({
                     placeholder: function(){
                         $(this).data('placeholder');
                     },
                    allowClear: true
                });
            }).fail(function(response) {
                console.log(response);
            });
            $('#common-modal').modal('show');
        };

      
    </script>
@stop

@slot('css')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2-bootstrap.min.css') }}">
@endslot

@include('common_pages.max_length')
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
@endcomponent